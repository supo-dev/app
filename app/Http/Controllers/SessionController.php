<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\LoginUser;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class SessionController
{
    public function show(#[CurrentUser] User $user): JsonResponse
    {
        return response()->json([
            'authenticated' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    public function store(LoginRequest $request, LoginUser $action): JsonResponse
    {
        $email = $request->string('email')->toString();
        $password = $request->string('password')->toString();

        $result = $action->handle($email, $password);

        return response()->json([
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
            ],
            'token' => $result['token'],
        ]);
    }

    public function destroy(#[CurrentUser] User $user): Response
    {
        $user->currentAccessToken()->delete();

        return response(status: 204);
    }
}
