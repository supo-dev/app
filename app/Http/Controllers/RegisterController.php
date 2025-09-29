<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\JsonResponse;

final class RegisterController
{
    public function store(RegistrationRequest $request): JsonResponse {}
}
