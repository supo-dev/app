<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('may follow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(route('follows.store', $targetUser));

    $response->assertStatus(201);

    expect($user->following()->count())->toBe(1)
        ->and($targetUser->followers()->count())->toBe(1);
});

it('may unfollow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $user->following()->attach($targetUser->id);

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('follows.destroy', $targetUser));

    $response->assertStatus(204);

    expect($user->following()->count())->toBe(0);
});
