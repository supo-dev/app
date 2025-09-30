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
        $username = $request->string('username')->toString();
        $password = $request->string('password')->toString();

        $action->handle($name, $email, $username, $password);

        return response(status: 201);
    }
}
