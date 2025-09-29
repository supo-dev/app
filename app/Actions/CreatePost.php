<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;
use App\Models\User;

final readonly class CreatePost
{
    public function handle(User $user, string $content): Post
    {
        return $user->posts()->create([
            'content' => $content,
        ]);
    }
}
