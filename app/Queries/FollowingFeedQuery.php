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
        $allFollowingIds = [...$followingIds, $this->user->id];

        return Post::query()
            ->where(function (Builder $query) use ($allFollowingIds): void {
                $query->whereIn('user_id', $allFollowingIds)
                    ->orWhereHas('reposts', function (Builder $repostQuery) use ($allFollowingIds): void {
                        $repostQuery->whereIn('user_id', $allFollowingIds);
                    });
            })
            ->with(['user', 'likes', 'reposts.user'])
            ->latest('updated_at');
    }
}
