<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreatePost;
use App\Actions\DeletePost;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

final readonly class PostController
{
    public function show(Post $post): Response
    {
        return response($post->toArray(), 200);
    }

    public function store(
        CreatePostRequest $request,
        #[CurrentUser] User $loggedInUser,
        CreatePost $action,
    ): Response {
        $content = $request->string('content')->toString();

        $action->handle($loggedInUser, $content);

        return response(status: 201);
    }

    public function destroy(DeletePostRequest $request, Post $post, DeletePost $action): Response
    {
        $action->handle($post);

        return response(status: 204);
    }
}
