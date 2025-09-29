<?php

declare(strict_types=1);

use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Users...
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::middleware(['auth', 'verified'])->group(function (): void {

    // Posts...
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Follows...
    Route::post('/follows/{user}', [FollowController::class, 'store'])->name('follows.store');
    Route::delete('/follows/{user}', [FollowController::class, 'destroy'])->name('follows.destroy');

    // Likes...
    Route::post('/likes/{post}', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{post}', [LikeController::class, 'destroy'])->name('likes.destroy');
});
