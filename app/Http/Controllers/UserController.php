<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUser;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class UserController
{
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
        ]);
    }

    public function store(CreateUserRequest $request, CreateUser $action): Response
    {
        $name = $request->string('name')->toString();
        $email = $request->string('email')->toString();
        $password = $request->string('password')->toString();

        $action->handle($name, $email, $password);

        return response(status: 201);
    }
}
