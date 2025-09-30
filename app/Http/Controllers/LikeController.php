<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\LikePost;
use App\Actions\UnlikePost;
use App\Http\Requests\LikePostRequest;
use App\Http\Requests\UnlikePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

final readonly class LikeController
{
    public function store(LikePostRequest $request, Post $post, LikePost $action, #[CurrentUser] User $loggedInUser): Response
    {
        $action->handle($loggedInUser, $post);

        return response(status: 201);
    }

    public function destroy(UnlikePostRequest $request, Post $post, UnlikePost $action, #[CurrentUser] User $loggedInUser): Response
    {
        $action->handle($loggedInUser, $post);

        return response(status: 204);
    }
}
