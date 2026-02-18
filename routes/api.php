<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\WebhookController;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\Route;

//публичные для регистрации и входа
Route::post('/registr', [AuthController::class, 'register']);
Route::post('/auth', [AuthController::class, 'login']);

//защищенные маршруты
Route::middleware('auth:sanctum')->group(function() {
    //курсы
    Route::get('/courses', [CourseController::class, 'index']); //все курсы
    Route::get('/courses/{course}', [CourseController::class, 'show']); //уроки конкретного курса
    Route::post('/courses/{course}/buy', [EnrollmentController::class, 'buy']); //запись на курс 
    //заказы (купленные курсы)
    Route::get('/orders', [EnrollmentController::class, 'index']);
    Route::get('/orders/{enrollment}', [EnrollmentController::class, 'cancel']); //отмена
});

Route::post('/payment-webhook', [WebhookController::class, 'handle']);
Route::post('/check-certificae', [EnrollmentController::class, 'checkCertificate']);