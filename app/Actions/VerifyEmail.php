<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use App\Support\EmailVerificationTokenManager;
use Illuminate\Validation\ValidationException;

final readonly class VerifyEmail
{
    public function __construct(
        private EmailVerificationTokenManager $tokenManager
    ) {}

    public function handle(
        User $user,
        string $token
    ): true {
        if ($user->email_verified_at !== null) {
            return true;
        }

        throw_unless($this->tokenManager->verify($user, $token), ValidationException::withMessages([
            'token' => ['The provided token is invalid or has expired.'],
        ]));

        $user->touch('email_verified_at');

        return true;
    }
}
