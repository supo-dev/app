<?php

declare(strict_types=1);

use App\Models\User;

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
    $response->assertJson([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => 'john@example.com',
        ],
    ]);

    $this->assertAuthenticatedAs($user);
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
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('sessions.show'));

    $response->assertOk();
    $response->assertJson([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
});

it('returns unauthenticated when not logged in', function () {
    $response = $this->getJson(route('sessions.show'));

    $response->assertStatus(401);
    $response->assertJson([
        'authenticated' => false,
    ]);
});

it('can logout a user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->deleteJson(route('sessions.destroy'));

    $response->assertStatus(204);
    $this->assertGuest();
});

it('requires authentication to logout', function () {
    $response = $this->deleteJson(route('sessions.destroy'));

    $response->assertStatus(401);
});
