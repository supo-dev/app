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
        LikePost $action,
        #[CurrentUser] User $loggedInUser
    ): Response {
        $action->handle($loggedInUser, $post);

        return response(status: 201);
    }

    public function destroy(
        Post $post,
        UnlikePost $action,
        #[CurrentUser] User $loggedInUser
    ): Response {
        $action->handle($loggedInUser, $post);

        return response(status: 204);
    }
}
