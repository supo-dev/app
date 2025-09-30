<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can create a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
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
    $response = $this->postJson(action([UserController::class, 'store']), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('validates email format when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'name' => 'John Doe',
        'email' => 'not-an-email',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates unique email when creating a user', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson(action([UserController::class, 'store']), [
        'name' => 'John Doe',
        'email' => 'existing@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates minimum password length when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'short',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
});

it('can show a user profile', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(action([UserController::class, 'show'], $user));

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'posts_count' => 0,
        'followers_count' => 0,
        'following_count' => 0,
    ]);
});

it('can update user profile', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);

    $user->refresh();
    expect($user->name)->toBe('Jane Doe')
        ->and($user->email)->toBe('jane@example.com');
});

it('can partially update user profile', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'name' => 'Jane Doe',
    ]);

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'name' => 'Jane Doe',
        'email' => 'john@example.com',
    ]);

    $user->refresh();
    expect($user->name)->toBe('Jane Doe')
        ->and($user->email)->toBe('john@example.com');
});

it('validates email uniqueness when updating user profile', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);
    $user = User::factory()->create(['email' => 'john@example.com']);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'email' => 'existing@example.com',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('allows keeping same email when updating user profile', function () {
    $user = User::factory()->create(['email' => 'john@example.com']);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'name' => 'Jane Doe',
        'email' => 'john@example.com',
    ]);

    $response->assertOk();
});

it('requires authentication to show user profile', function () {
    $user = User::factory()->create();

    $response = $this->getJson(action([UserController::class, 'show'], $user));

    $response->assertStatus(401);
});

it('requires authentication to update user profile', function () {
    $user = User::factory()->create();

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'name' => 'Jane Doe',
    ]);

    $response->assertStatus(401);
});

it('cannot update another users profile', function () {
    $user = User::factory()->create(['name' => 'John Doe']);
    $otherUser = User::factory()->create(['name' => 'Other User']);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $otherUser), [
        'name' => 'Hacked Name',
    ]);

    $response->assertStatus(403);

    $otherUser->refresh();
    expect($otherUser->name)->toBe('Other User');
});

it('can delete own account', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(action([UserController::class, 'destroy']));

    $response->assertStatus(204);

    expect(User::find($user->id))->toBeNull();
});

it('requires authentication to delete account', function () {
    $response = $this->deleteJson(action([UserController::class, 'destroy']));

    $response->assertStatus(401);
});
