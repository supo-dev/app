<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class LoginUser
{
    /**
     * @return array{user: User}
     */
    public function handle(string $username, string $password): array
    {
        $user = User::query()->where('username', $username)->first();

        throw_if(! $user || ! Hash::check($password, $user->password), ValidationException::withMessages([
            'username' => ['The provided credentials are incorrect.'],
        ]));

        return [
            'user' => $user,
        ];
    }
}
