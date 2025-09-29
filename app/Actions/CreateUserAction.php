<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class CreateUserAction
{
    public function handle(
        string $nickname,
        string $name,
        string $email,
        string $password,
    ): User {

        return User::query()
            ->create([
                'nickname' => $nickname,
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);

    }
}
