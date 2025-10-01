<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UpdateUser
{
    public function __construct(
        private SendEmailVerification $sendEmailVerification
    ) {}

    public function handle(
        User $user,
        ?string $name = null,
        ?string $email = null
    ): User {
        $data = [];

        if ($name !== null) {
            $data['name'] = $name;
        }

        if ($email !== null && $email !== $user->email) {
            $data['email'] = $email;
            $data['email_verified_at'] = null;
        }

        $user->update($data);

        if ($user->wasChanged('email')) {
            $this->sendEmailVerification->handle($user);
        }

        return $user;
    }
}
