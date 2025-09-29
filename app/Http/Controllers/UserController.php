<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUser;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\Response;

final readonly class UserController
{
    public function store(CreateUserRequest $request, CreateUser $action): Response
    {
        $name = $request->string('name')->toString();
        $email = $request->string('email')->toString();
        $password = $request->string('password')->toString();

        $action->handle($name, $email, $password);

        return response(status: 201);
    }
}
