<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class FollowUser
{
    public function handle(User $user, User $toFollow): void
    {
        if ($user->following()->where('user_id', $toFollow->id)->exists()) {
            return;
        }

        $user->following()->attach($toFollow->id);
    }
}
