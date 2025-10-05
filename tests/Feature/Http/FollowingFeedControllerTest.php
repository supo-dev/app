<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can get following feed', function (): void {
    $user = User::factory()->create();
    $followedUser = User::factory()->create();
    $post = Post::factory()->for($followedUser)->create();

    $user->following()->attach($followedUser);

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/following');

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'id',
                'content',
                'created_at',
                'updated_at',
                'user' => ['id', 'username', 'email'],
                'likes' => [],
            ],
        ],
        'current_page',
        'per_page',
        'total',
    ]);

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.id'))->toBe($post->id);
});

it('can paginate following feed', function (): void {
    $user = User::factory()->create();
    $followedUser = User::factory()->create();
    Post::factory()->for($followedUser)->count(20)->create();

    $user->following()->attach($followedUser);

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/following?per_page=5&page=1');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(5);
    expect($response->json('per_page'))->toBe(5);
    expect($response->json('current_page'))->toBe(1);
    expect($response->json('total'))->toBe(20);
});

it('requires authentication for following feed', function (): void {
    $response = $this->getJson('/api/feeds/following');
    $response->assertUnauthorized();
});

it('returns empty following feed when user follows no one', function (): void {
    $user = User::factory()->create();

    // posts exist but user follows no one
    Post::factory()->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/following');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
    expect($response->json('total'))->toBe(0);
});
