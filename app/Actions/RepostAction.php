<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\Repost;
use App\Models\User;

final readonly class RepostAction
{
    public function handle(Post $post, User $user): void
    {
        Repost::query()->firstOrCreate([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }
}
