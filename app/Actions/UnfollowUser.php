<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UnfollowUser
{
    public function handle(User $user, User $toUnfollow): void
    {
        $user->following()->where('user_id', $toUnfollow->id)->doesntExistOr(
            fn () => $user->following()->detach($toUnfollow->id)
        );
    }
}
