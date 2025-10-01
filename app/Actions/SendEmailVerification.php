<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Support\EmailVerificationTokenManager;

final readonly class SendEmailVerification
{
    public function __construct(
        private EmailVerificationTokenManager $tokenManager
    ) {}

    public function handle(User $user): void
    {
        $token = $this->tokenManager->generate($user);

        $user->notify(new EmailVerificationNotification($token));
    }
}
