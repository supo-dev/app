<?php

declare(strict_types=1);

use App\Actions\CreatePost;
use App\Models\Post;
use App\Models\User;

it('may create a post', function (): void {
    $user = User::factory()->create();
    $content = 'This is a test post content.';
    $action = app(CreatePost::class);

    $post = $action->handle($user, $content);

    expect($post)->toBeInstanceOf(Post::class)
        ->and($post->user_id)->toBe($user->id)
        ->and($post->content)->toBe($content);
});
