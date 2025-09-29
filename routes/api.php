<?php

declare(strict_types=1);

use App\Http\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::as('auth.')->group(function () {

    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register');

});
