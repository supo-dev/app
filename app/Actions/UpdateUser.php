<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UpdateUser
{
    public function handle(
        User $user,
        ?string $username = null,
        ?string $bio = null
    ): User {
        $data = [];

        if ($username !== null) {
            $data['username'] = $username;
        }

        if ($bio !== null) {
            $data['bio'] = $bio;
        }

        $user->update($data);

        return $user;
    }
}
