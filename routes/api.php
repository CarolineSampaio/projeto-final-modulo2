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

    Route::prefix('exercises')->group(function () {
        Route::post('/', [ExerciseController::class, 'store']);
        Route::get('/', [ExerciseController::class, 'index']);
        Route::delete('/{id}', [ExerciseController::class, 'destroy']);
    });

    Route::prefix('students')->group(function () {
        Route::get('/{id}/workouts', [WorkoutController::class, 'show']);
        Route::get('/export', [WorkoutController::class, 'exportStudentWorkouts']);

        Route::post('/', [StudentController::class, 'store'])->middleware(ValidatePlanLimit::class);
        Route::get('/', [StudentController::class, 'index']);
        Route::get('/{id}', [StudentController::class, 'show']);
        Route::delete('/{id}', [StudentController::class, 'destroy']);
        Route::put('/{id}', [StudentController::class, 'update']);
    });

    Route::post('workouts', [WorkoutController::class, 'store']);
});

Route::post('users', [UserController::class, 'store']);

Route::post('login', [AuthController::class, 'store']);
