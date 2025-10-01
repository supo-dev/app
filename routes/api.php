<?php

declare(strict_types=1);

use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Sessions...
Route::post('/sessions', [SessionController::class, 'store']);

// Users...
Route::post('/users', [UserController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function (): void {

    // Sessions...
    Route::get('/sessions', [SessionController::class, 'show']);
    Route::delete('/sessions', [SessionController::class, 'destroy']);

    // Users...
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users', [UserController::class, 'destroy']);

    // Posts...
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    // Follows...
    Route::post('/follows/{user}', [FollowController::class, 'store']);
    Route::delete('/follows/{user}', [FollowController::class, 'destroy']);

    // Likes...
    Route::post('/likes/{post}', [LikeController::class, 'store']);
    Route::delete('/likes/{post}', [LikeController::class, 'destroy']);

    // Email Verification...
    Route::post('/email/send-verification', [EmailVerificationController::class, 'send']);
    Route::post('/email/verify', [EmailVerificationController::class, 'verify']);
});
