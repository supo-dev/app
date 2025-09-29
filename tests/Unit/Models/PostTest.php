<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

test('to array', function () {
    $post = Post::factory()->create()->fresh();

    expect(array_keys($post->toArray()))
        ->toBe([
            'id',
            'user_id',
            'content',
            'created_at',
            'updated_at',
        ]);
});

test('relation user', function () {
    $post = Post::factory()->create()->fresh();

    expect($post->user)
        ->toBeInstanceOf(User::class)
        ->and($post->user_id)->toBe($post->user->id);
});
