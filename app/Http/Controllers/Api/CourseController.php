<?php

namespace App\Http\Controllers\Api;

use App\Models\Lesson;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::paginate(10);
        $data = $courses->map(function ($course)
        {
            return [
                'id' => $course->id,
                'name' => $course->title,
                'description' => $course->description,
                'hours' => $course->duration,
                'img' => asset('storage/' . $course->cover),
                'start_date' => $course->start_date->format('d-m-Y'),
                'end_date' => $course->end_date->format('d-m-Y'),
                'price' => $course->price,
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                    'total' => $courses->lastPage(),
                    'current' => $courses->currentPage(),
                    'per_page' => $courses->perPage(),
                ],
        ]);
    }
    public function show(Course $course)
    {
        $lessons = $course->lessons()->get()->map(function ($lesson){
            return [
                'id' => $lesson->id,
                'name' => $lesson->title,
                'description' => $lesson->description,
                'video_link' => $lesson->video_link,
                'hours' => $lesson->duration,
            ];
        });

        return response()->json([
            'data' => $lessons
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
