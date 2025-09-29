<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreatePost;
use App\Actions\DeletePost;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Response;

/**
 * - [GET] /posts/{post_id} - Get Post by ID
 * - [POST] /posts - Create New Post
 * - [DELETE] /posts/{post_id} - Delete Post by ID
 */
final readonly class PostController
{
    public function show(Post $post): Response
    {
        return response($post->toArray(), 200);
    }

    public function store(CreatePostRequest $request, CreatePost $action): Response
    {
        $content = $request->string('content')->toString();

        $user = $request->user();

        assert($user instanceof User);

        $action->handle($user, $content);

        return response(status: 201);
    }

    public function destroy(DeletePostRequest $request, Post $post, DeletePost $action): Response
    {
        $action->handle($post);

        return response(status: 204);
    }
}
