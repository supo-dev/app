<?php

declare(strict_types=1);

use App\Models\Like;
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

test('relation likes', function () {
    $post = Post::factory()->hasLikes(3)->create()->fresh();

    expect($post->likes)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Like::class);
});

test('relation reposts', function () {
    $post = Post::factory()->create();
    $users = User::factory()->count(2)->create();

    foreach ($users as $user) {
        $post->reposts()->create(['user_id' => $user->id]);
    }

    $post = $post->fresh();

    expect($post->reposts)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(App\Models\Repost::class);
});
