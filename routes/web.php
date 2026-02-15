<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\EnrollmentController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Гостевые маршруты
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Административная панель (защищена auth и admin)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::resource('courses', CourseController::class)->except('show')->names('courses');
    
    // Вложенные ресурсы уроков (курс/{course}/lessons)
    Route::resource('courses.lessons', LessonController::class)->names('lessons');
    

    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    
    Route::post('/certificate/{enrollment}', [EnrollmentController::class, 'printCertificate'])->name('certificate.print');
});

