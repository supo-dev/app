<?php

declare(strict_types=1);

use App\Actions\DeletePost;
use App\Models\Post;

it('may delete a post', function (): void {
    $post = Post::factory()->create();
    $action = app(DeletePost::class);

    $action->handle($post);

    $post = $post->fresh();

    expect($post)->toBeNull();
});
