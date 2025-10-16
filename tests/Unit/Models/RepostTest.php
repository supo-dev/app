<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Repost;
use App\Models\User;

it('belongs to a user', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $repost = Repost::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($repost->user)->toBeInstanceOf(User::class);
    expect($repost->user->id)->toBe($user->id);
});

it('belongs to a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $repost = Repost::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect($repost->post)->toBeInstanceOf(Post::class);
    expect($repost->post->id)->toBe($post->id);
});

it('has the correct fillable attributes', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    $repost = new Repost;
    $repost->user_id = $user->id;
    $repost->post_id = $post->id;
    $repost->save();

    expect($repost->user_id)->toBe($user->id);
    expect($repost->post_id)->toBe($post->id);
    expect($repost->created_at)->not->toBeNull();
    expect($repost->updated_at)->not->toBeNull();
});
