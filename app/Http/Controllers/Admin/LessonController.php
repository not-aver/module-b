<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Course $course)
    {
        $lessons = $course->lessons()->orderBy('id')->get();
        return view('admin.lessons.index', compact('course', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        if($course->lessons()->count() >= 5){
            return back()->withErrors(['limit' => 'Курс не может содержать более 5 уроков.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'video_link' => 'nullable|url|regex:/^https:\/\/super-tube\.cc\/video\/[a-zA-Z0-9]+$/',
            'duration' => 'required|integer|min:1|max:4' 
        ]);

        $validated['course_id'] = $course->id;
        Lesson::create($validated);
        return redirect()->route('admin.lessons.index', $course)->with('succes', 'Урок создан');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course, Lesson $lesson)
    {
        if($lesson->course_id !== $course->id){
            abort(404, 'урок не связан с курсом');
        }
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, Lesson $lesson)
    {
        if($lesson->course_id !== $course->id){
            abort(404, 'урок не связан с курсом');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'video_link' => 'nullable|url|regex:/^https:\/\/super-tube\.cc\/video\/[a-zA-Z0-9]+$/',
            'duration' => 'required|integer|min:1|max:240' 
        ]);

        $validated['course_id'] = $course->id;
        $lesson->update($validated);
        return redirect()->route('admin.lessons.index', $course)->with('success', 'урок обновлен');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, Lesson $lesson)
    {
        if($lesson->course_id !== $course->id){
            abort(404, 'урок не связан с курсом');
        }

        if($course->enrollments()->where('payment_status', 'success')->exists()){
            return back()->withErrors(['delete' => 'урок не может быть удален, так как есть записи на курс.']);
        }

        $lesson->delete();
        return redirect()->route('admin.lessons.index', $course)->with('success', 'урок удален.');
    }
}
