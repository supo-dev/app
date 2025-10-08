<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\User;

final readonly class LikeAction
{
    public function handle(Post $post, User $user): void
    {
        $post->likes()->create([
            'user_id' => $user->id,
        ]);
    }
}
