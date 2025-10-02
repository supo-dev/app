<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Queries\FollowingFeed;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class FollowingFeedController
{
    /**
     * Get the following feed for the authenticated user.
     */
    public function __invoke(
        Request $request,
        #[CurrentUser] User $user
    ): JsonResponse {
        $followingFeed = new FollowingFeed($user);

        $posts = $followingFeed->builder()
            ->paginate(
                perPage: $request->integer('per_page', 15),
                page: $request->integer('page', 1)
            );

        return response()->json($posts);
    }
}
