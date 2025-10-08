<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

final readonly class TrendingFeedQuery
{
    /**
     * @return Builder<Post>
     */
    public function builder(): Builder
    {
        return Post::query()
            ->with(['user', 'likes'])
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->orderByDesc('created_at');
    }
}
