<?php

declare(strict_types=1);

use App\Models\User;

it('may follow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();

    $response = $this->actingAs($user)
        ->post(route('follows.store', [$targetUser]));

    $response->assertStatus(201);

    expect($user->following()->count())->toBe(1)
        ->and($targetUser->followers()->count())->toBe(1);
});

it('may unfollow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $user->following()->attach($targetUser->id);

    $response = $this->actingAs($user)
        ->delete(route('follows.destroy', [$targetUser]));

    $response->assertStatus(204);

    expect($user->following()->count())->toBe(0);
});
