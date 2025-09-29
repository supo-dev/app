<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

final readonly class UnfollowUser
{
    public function handle(User $user, User $toUnfollow): void
    {
        if ($user->following()->where('user_id', $toUnfollow->id)->doesntExist()) {
            return;
        }

        $user->following()->detach($toUnfollow->id);
    }
}
