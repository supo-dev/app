<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can login a user', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson(route('sessions.store'), [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'user' => [
            'id',
            'username',
            'email',
        ],
        'token',
    ]);
    $response->assertJson([
        'user' => [
            'id' => $user->id,
            'username' => $user->username,
            'email' => 'john@example.com',
        ],
    ]);

    expect($response->json('token'))->toBeString();
});

it('validates login credentials', function () {
    $response = $this->postJson(route('sessions.store'), [
        'email' => 'invalid@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates required login fields', function () {
    $response = $this->postJson(route('sessions.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

it('can check current session when authenticated', function () {
    $user = User::factory()->create([
        'username' => 'doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(route('sessions.show'));

    $response->assertOk();
    $response->assertJson([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'username' => 'doe',
            'email' => 'john@example.com',
        ],
    ]);
});

it('returns unauthenticated when not logged in', function () {
    $response = $this->getJson(route('sessions.show'));

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});

it('can logout a user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(route('sessions.destroy'));

    $response->assertStatus(204);

    // Verify token was deleted
    expect($user->tokens()->count())->toBe(0);
});

it('requires authentication to logout', function () {
    $response = $this->deleteJson(route('sessions.destroy'));

    $response->assertStatus(401);
});
