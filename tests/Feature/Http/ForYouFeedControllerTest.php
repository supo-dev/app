<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can get for-you feed', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $post1 = Post::factory()->for($user)->create();
    $post2 = Post::factory()->for($otherUser)->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/for-you');

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

    expect($response->json('data'))->toHaveCount(2);
});

it('can paginate for-you feed', function (): void {
    $user = User::factory()->create();
    Post::factory()->count(20)->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/for-you?per_page=5&page=1');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(5);
    expect($response->json('per_page'))->toBe(5);
    expect($response->json('current_page'))->toBe(1);
    expect($response->json('total'))->toBe(20);
});

it('can return for-you feed without authentication', function (): void {
    Post::factory()->count(3)->create();

    $response = $this->getJson('/api/feeds/for-you');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(3);
});

it('returns empty for-you feed when no posts exist', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);
    $response = $this->getJson('/api/feeds/for-you');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(0);
    expect($response->json('total'))->toBe(0);
});
