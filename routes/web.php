<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json(['message' => 'Sup Dev API is running.'], 200));
