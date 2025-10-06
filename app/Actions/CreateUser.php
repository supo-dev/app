<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use SensitiveParameter;

final readonly class CreateUser
{
    public function handle(
        string $username,
        #[SensitiveParameter] string $password
    ): User {
        return User::query()->create([
            'username' => $username,
            'password' => $password,
        ]);
    }
}
