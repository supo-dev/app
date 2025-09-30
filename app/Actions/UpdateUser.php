<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UpdateUser
{
    public function handle(User $user, ?string $name = null, ?string $email = null): User
    {
        $data = [];

        if ($name !== null) {
            $data['name'] = $name;
        }

        if ($email !== null) {
            $data['email'] = $email;
        }

        if ($data === []) {
            return $user;
        }

        return tap($user)->update($data);
    }
}
