<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

Route::prefix('v1')->group(function () {
    // Projects
    Route::apiResource('projects', ProjectController::class);
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore']);

    // Tasks
    Route::apiResource('tasks', TaskController::class);
    Route::post('tasks/{id}/restore', [TaskController::class, 'restore']);
    Route::post('tasks/{task}/assign', [TaskController::class, 'assignUsers']);

    // Users
    Route::apiResource('users', UserController::class);
    Route::post('users/{id}/restore', [UserController::class, 'restore']);
    Route::get('users/{user}/tasks', [UserController::class, 'tasks']);
});
