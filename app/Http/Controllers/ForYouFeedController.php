<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\ForYouFeedQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class ForYouFeedController
{
    /**
     * Get the for-you feed.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $forYouFeed = new ForYouFeedQuery();

        $posts = $forYouFeed->builder()
            ->paginate(
                perPage: $request->integer('per_page', 15),
                page: $request->integer('page', 1)
            );

        return response()->json($posts);
    }
}
