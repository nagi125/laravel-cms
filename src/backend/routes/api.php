<?php

use App\Http\Controllers\Api\Admin\PostController as AdminPostController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\HealthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/health', HealthController::class);

Route::prefix('auth')->group(function (): void {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });
});

Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);

Route::prefix('admin')->middleware('auth:sanctum')->group(function (): void {
    Route::get('/posts', [AdminPostController::class, 'index']);
    Route::post('/posts', [AdminPostController::class, 'store']);
    Route::get('/posts/{id}', [AdminPostController::class, 'show'])->whereNumber('id');
    Route::put('/posts/{id}', [AdminPostController::class, 'update'])->whereNumber('id');
    Route::delete('/posts/{id}', [AdminPostController::class, 'destroy'])->whereNumber('id');
});
