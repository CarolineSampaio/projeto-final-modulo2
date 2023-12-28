<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidatePlanLimit;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::post('exercises', [ExerciseController::class, 'store']);
    Route::get('exercises', [ExerciseController::class, 'index']);
    Route::delete('exercises/{id}', [ExerciseController::class, 'destroy']);

    Route::post('workouts', [WorkoutController::class, 'store']);
    Route::get('students/{id}/workouts', [WorkoutController::class, 'show']);
    Route::get('students/export', [WorkoutController::class, 'exportStudentWorkouts']);

    Route::post('students', [StudentController::class, 'store'])->middleware(ValidatePlanLimit::class);
    Route::get('students', [StudentController::class, 'index']);
    Route::get('students/{id}', [StudentController::class, 'show']);
    Route::delete('students/{id}', [StudentController::class, 'destroy']);
    Route::put('students/{id}', [StudentController::class, 'update']);
});

Route::post('users', [UserController::class, 'store']);

Route::post('login', [AuthController::class, 'store']);
