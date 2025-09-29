<?php

declare(strict_types=1);

use App\Actions\LikePost;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('may like a post', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $action = app(LikePost::class);

    $like = $action->handle($user, $post);

    expect($like)->toBeInstanceOf(Like::class)
        ->and($like->user_id)->toBe($user->id)
        ->and($like->post_id)->toBe($post->id);
});
