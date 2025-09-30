<?php

declare(strict_types=1);

use App\Http\Controllers\PostController;
use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('may view a post', function () {
    $post = Post::factory()->create()->fresh();
    $user = $post->user;

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(action([PostController::class, 'show'], $post));

    $response->assertStatus(200);
    $response->assertJson($post->fresh()->toArray());
});

it('can create a post', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(action([PostController::class, 'store']), [
        'content' => 'This is a test post.',
    ]);

    $response->assertStatus(201);

    $post = $user->posts()->first();

    expect($user->posts)
        ->toHaveCount(1)
        ->and($post->content)->toBe('This is a test post.');
});

it('may delete a post', function () {
    $post = Post::factory()->create();
    $user = $post->user;

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(action([PostController::class, 'destroy'], $post));

    $response->assertStatus(204);

    $post = $post->fresh();

    expect($post)->toBeNull();
});

test('cannot delete another users post', function () {
    $post = Post::factory()->create();
    $otherUser = User::factory()->create();

    Sanctum::actingAs($otherUser, ['*']);

    $response = $this->deleteJson(action([PostController::class, 'destroy'], $post));

    $response->assertStatus(403);

    $post = $post->fresh();

    expect($post)->toBeInstanceOf(Post::class);
});
