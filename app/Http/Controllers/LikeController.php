<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\LikePost;
use App\Actions\UnlikePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

final readonly class LikeController
{
    public function store(
        Post $post,
        #[CurrentUser] User $loggedInUser,
        LikePost $action
    ): Response {
        $action->handle($loggedInUser, $post);

        return response(status: 201);
    }

    public function destroy(
        Post $post,
        #[CurrentUser] User $loggedInUser,
        UnlikePost $action
    ): Response {
        $action->handle($loggedInUser, $post);

        return response(status: 204);
    }
}
