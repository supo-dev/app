<?php

declare(strict_types=1);

use App\Http\Controllers\FollowController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function (): void {

    // Posts...
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Follows...
    Route::post('/follows/{user}', [FollowController::class, 'store'])->name('follows.store');
    Route::delete('/follows/{user}', [FollowController::class, 'destroy'])->name('follows.destroy');
});
