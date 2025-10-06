<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;

final readonly class FollowingFeedQuery
{
    public function __construct(
        #[CurrentUser] private User $user
    ) {}

    /**
     * @return Builder<Post>
     */
    public function builder(): Builder
    {
        $followingIds = $this->user->following()->pluck('users.id');

        return Post::query()
            ->whereIn('user_id', $followingIds)
            ->with(['user', 'likes'])
            ->latest('updated_at');
    }
}
