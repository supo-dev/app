<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UpdateUser
{
    public function handle(
        User $user,
        ?string $username = null
    ): User {
        $data = [];

        if ($username !== null) {
            $data['username'] = $username;
        }

        $user->update($data);

        return $user;
    }
}
