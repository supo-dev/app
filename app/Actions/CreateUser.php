<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use SensitiveParameter;

final readonly class CreateUser
{
    public function __construct(
        private SendEmailVerification $sendEmailVerification
    ) {}

    public function handle(
        string $name,
        string $email,
        #[SensitiveParameter] string $password
    ): User {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => null,
        ]);

        $this->sendEmailVerification->handle($user);

        return $user;
    }
}
