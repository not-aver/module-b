<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(5);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'duration' => 'required|integer|max:10',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cover' => 'required|image|max:2048'
        ]);

        if ($file = $request->file('cover')) {
            // 1. Имя по ТЗ и создание ресурса
            $name = 'courses/mpic' . time() . '.' . $file->extension();
            $src = ($file->extension() == 'png') ? imagecreatefrompng($file) : imagecreatefromjpeg($file);
            
            // 2. Математика: находим сторону квадрата и центр
            $w = imagesx($src); $h = imagesy($src);
            $size = min($w, $h);
            $x = ($w - $size) / 2;
            $y = ($h - $size) / 2;

            // 3. Ресайз
            $dst = imagecreatetruecolor(300, 300);
            imagecopyresampled($dst, $src, 0, 0, $x, $y, 300, 300, $size, $size);

            // 4. Сохранение (Laravel Storage + GD)
            $fullPath = storage_path('app/public/' . $name);
            @mkdir(dirname($fullPath), 0775, true); // Создаем папку если нет
            ($file->extension() == 'png') ? imagepng($dst, $fullPath) : imagejpeg($dst, $fullPath);

            $validated['cover'] = $name;
        }

        Course::create($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Курс создан');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'duration' => 'required|integer|max:10',
            'price' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cover' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('cover')) {
            // Удалить старый файл
            if ($course->cover && Storage::disk('public')->exists($course->cover)) {
                Storage::disk('public')->delete($course->cover);
            }

            $file = $request->file('cover');
            $filename = 'mpic' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('courses', $filename, 'public');

            // Миниатюра
            $fullPath = storage_path('app/public/' . $path);
            $manager = new ImageManager(new Driver());
            $image = $manager->read($fullPath);
            $image->scale(width: 300, height: 300);
            $image->save($fullPath);

            $validated['cover'] = $path;
        }

        $course->update($validated);
        return redirect()->route('admin.courses.index')->with('success', 'Курс обновлён');
    }

    public function destroy(Course $course)
    {
        // Проверяем наличие записей (enrollments) на этот курс
        if ($course->enrollments()->exists()) {
            return back()->withErrors(['delete' => 'Нельзя удалить курс, на который есть записи студентов.']);
        }

        // Удаляем изображение
        if ($course->cover && Storage::disk('public')->exists($course->cover)) {
            Storage::disk('public')->delete($course->cover);
        }

        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Курс удалён');
    }
}