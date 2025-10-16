<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Builder;

final readonly class UserPostsQuery
{
    public function __construct(
        #[CurrentUser] private User $user
    ) {}

    /**
     * @return Builder<Post>
     */
    public function builder(): Builder
    {
        return Post::query()
            ->where('user_id', $this->user->id)
            ->with(['user', 'likes', 'reposts'])
            ->latest('created_at');
    }
}
