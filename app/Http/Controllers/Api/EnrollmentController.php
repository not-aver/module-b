<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollments = Enrollment::with('course')->where('user_id', Auth::id())->get();

        $data = $enrollments->map(function($enrollment){
            $course = $enrollment->course;
            return [
                'id' => $enrollment->id,
                'payment_status' => $enrollment->payment_status,
                'course' => [
                    'id' => $course->id,
                    'name' => $course->title,
                    'description' => $course->description,
                    'hours' => $course->duration,
                    'img' => asset('storage/' . $course->cover),
                    'start_date' => $course->start_date->format('d-m-Y'),
                    'end_date' => $course->end_date->format('d-m-Y'),
                    'price' => $course->price,
                ]
            ];
        });
        return response()->json(['data' => $data],200);
    }
    public function buy(Course $course)
    {
        if($course->start_date->isPast()){
            return response()->json(['message' => 'course already started'], 400);
        }

        $existing = Enrollment::where('user_id', Auth::id())->where('course_id', $course->id)->first();
        if($existing){
            return response()->json([
                'message' => 'already enrolled',
            ], 400);
        }

        $enrollment = Enrollment::create([
            'order_id' => 'ORD-' . Str::random(10),
            'course_id' => $course->id,
            'user_id' => Auth::id(),
            'email' => Auth::user()->email,
            'payment_status' => 'pending'
        ]);

        return response()->json([
            'pay_url' => url('/api/payment-redirect' . $enrollment->order_id)
        ]);
    }

    public function cancel(Enrollment $enrollment){
        //если статус оплаты НЕ pending и НЕ failed, а любой другой - возвращаем ошибку
        if(!in_array($enrollment->payment_status, ['pending', 'failed'])){
            return response()->json(['status' => 'was payed'], 418);
        }

        $enrollment->delete();
        return response()->json(['status' => 'success']);
    }

    public function checkCertificate(Request $request){
        $validated = $request->validate([
            'certificate_number' => 'required|string'
        ]);

        $number = $validated['certificate_number'];
        $lastDigit = substr($number, -1);

        if($lastDigit === '1'){
            return response()->json(['status' => 'success']);
        } else{
            return response()->json(['status' => 'failed']);
        }
    }
}
