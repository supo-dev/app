<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\Repost;
use App\Models\User;

final readonly class UnrepostAction
{
    public function handle(Post $post, User $user): void
    {
        Repost::query()->where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->delete();
    }
}
