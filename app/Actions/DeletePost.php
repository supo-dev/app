<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Post;

final readonly class DeletePost
{
    public function handle(Post $post): void
    {
        $post->delete();
    }
}
