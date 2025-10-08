<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\User;

final readonly class UnlikeAction
{
    public function handle(Post $post, User $user): void
    {
        $post->likes()->where('user_id', $user->id)->delete();
    }
}
