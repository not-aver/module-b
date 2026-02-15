<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::all();
        $query = Enrollment::with(['user', 'course']);

        if($request->filled('course_id')){
            $query->where('course_id', $request->course_id);
        }

        $enrollments = $query->paginate(15);
        return view('admin.enrollments.index', compact('enrollments', 'courses'));
    }
    public function printCertificate(Enrollment $enrollment)
    {
        if($enrollment->payment_status !== 'success'){
            abort(403, 'курс не оплачен.');
        }

        $fakeResponse = ['course_number' => 'ABC123'];
        $firstPart = $fakeResponse['course_number'];
        $secondPart = rand(10000, 99999) . 1;
        $certificateNumber = $firstPart . $secondPart;

        return view('admin.certificate', [
            'student' => $enrollment->user,
            'course' => $enrollment->course,
            'certificateNumber' => $certificateNumber,
        ]);

    }

    public function printRealCertificate(Enrollment $enrollment)
    {
        if($enrollment->payment_status !== 'success'){
            abort(403, 'курс не оплачен.');
        }

        $response = Http::withHeaders([
            'ClientId' => config('services.certificate.client_id'),
            'Accept' => 'application/json',
            'Content-type' => 'application/json',
        ])->post(config('services.certificate.url') . '/create-sertificate', [ //возможно ошибка в (s)ertificate. заменить на (c), если вдруг что
            'student_id' => $enrollment->user_id,
            'course_id' => $enrollment->course_id,
        ]);

        if($response->failed()){
            return back()->withErrors(['certificate' => 'не удалось сгенерировать.']);
        }

        $firstPart = $response->json('course_number');
        $secondPart = rand(10000, 99999) . 1;
        $certificateNumber = $firstPart . $secondPart;

        return view('admin.certificate', [
            'student' => $enrollment->user,
            'course' => $enrollment->course,
            'certificateNumber' => $certificateNumber,
        ]);
    }

}
