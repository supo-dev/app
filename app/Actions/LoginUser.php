<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class LoginUser
{
    /**
     * @return array{user: User, token: string}
     */
    public function handle(string $email, string $password): array
    {
        $user = User::query()->where('email', $email)->first();

        throw_if(! $user || ! Hash::check($password, $user->password), ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]));

        return [
            'user' => $user,
        ];
    }
}
