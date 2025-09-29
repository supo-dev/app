<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class FollowUser
{
    public function handle(User $user, User $toFollow): void
    {
        $user->following()->where('user_id', $toFollow->id)->existsOr(
            fn () => $user->following()->attach($toFollow->id)
        );
    }
}
