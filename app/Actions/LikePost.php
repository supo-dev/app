<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

final readonly class LikePost
{
    public function handle(User $user, Post $post): Like
    {
        return $user->likes()->create([
            'post_id' => $post->id,
        ]);
    }
}
