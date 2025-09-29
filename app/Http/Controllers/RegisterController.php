<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

final class RegisterController
{
    /**
     * @throws Throwable
     */
    public function store(RegistrationRequest $request, CreateUserAction $createUserAction): JsonResponse
    {

        $user = $createUserAction->handle(
            nickname: $request->string('nickname')->toString(),
            name: $request->string('name')->toString(),
            email: $request->string('email')->toString(),
            password: $request->string('password')->toString(),
        );

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 201);

    }
}
