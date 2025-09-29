<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\LikePost;
use App\Actions\UnlikePost;
use App\Http\Requests\LikePostRequest;
use App\Http\Requests\UnlikePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Response;

/**
 * - [POST] /likes/{post_id} - Like a Post
 * - [DELETE] /likes/{post_id} - Unlike a Post
 */
final readonly class LikeController
{
    public function store(LikePostRequest $request, Post $post, LikePost $action): Response
    {
        $user = $request->user();

        assert($user instanceof User);

        $action->handle($user, $post);

        return response('', 201);
    }

    public function destroy(UnlikePostRequest $request, Post $post, UnlikePost $action): Response
    {
        $user = $request->user();

        assert($user instanceof User);

        $action->handle($user, $post);

        return response('', 204);
    }
}
