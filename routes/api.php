<?php

declare(strict_types=1);

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', fn (Request $request) => $request->user())->middleware('auth:sanctum');

Route::as('auth.')->group(function (): void {

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register');

});
