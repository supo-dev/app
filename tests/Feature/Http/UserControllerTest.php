<?php

declare(strict_types=1);

use App\Models\User;

it('can create a user', function () {
    $response = $this->post(route('users.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'username' => 'johndoe',
        'password' => 'password123',
    ]);

    $response->assertStatus(201);

    $user = User::query()->where('email', 'john@example.com')->first();

    expect($user)
        ->not->toBeNull()
        ->and($user->name)->toBe('John Doe')
        ->and($user->email)->toBe('john@example.com');
});

it('validates required fields when creating a user', function () {
    $response = $this->postJson(route('users.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('validates email format when creating a user', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => 'John Doe',
        'email' => 'not-an-email',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates unique email when creating a user', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson(route('users.store'), [
        'name' => 'John Doe',
        'email' => 'existing@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates minimum password length when creating a user', function () {
    $response = $this->postJson(route('users.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'short',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
});
