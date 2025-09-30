<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use SensitiveParameter;

final readonly class CreateUser
{
    public function handle(
        string $name,
        string $email,
        string $username,
        #[SensitiveParameter] string $password
    ): User {
        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ]);
    }
}
