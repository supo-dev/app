<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('may like a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('likes.store', $post));

    $response->assertStatus(201);

    expect($user->likes()->where('post_id', $post->id)->exists())->toBeTrue();
});

it('may unlike a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Create a like first
    Like::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('likes.destroy', $post));

    $response->assertStatus(204);

    expect($user->likes()->where('post_id', $post->id)->exists())->toBeFalse();
});

it('cannot like a post twice', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Create a like first
    Like::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('likes.store', $post));

    $response->assertStatus(201);
});

it('can unlike a post that is not liked', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('likes.destroy', $post));

    $response->assertStatus(204);
});
