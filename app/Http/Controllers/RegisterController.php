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

        $user = $createUserAction->handle($request->toDto());

        return response()->json($user, 201);

    }
}
