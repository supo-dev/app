<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\User;

final readonly class UnlikePost
{
    public function handle(User $user, Post $post): void
    {
        $user->likes()->where('post_id', $post->id)->delete();
    }
}
