<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

final readonly class FollowingFeedQuery
{
    public function __construct(
        private User $user
    ) {}

    /**
     * @return Builder<Post>
     */
    public function builder(): Builder
    {
        $followingUsersQuery = $this->user->following()->select('users.id');

        return Post::query()
            ->whereIn('user_id', $followingUsersQuery)
            ->with(['user', 'likes'])
            ->latest('updated_at');
    }
}
