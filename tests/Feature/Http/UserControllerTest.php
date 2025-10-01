<?php

declare(strict_types=1);

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Notification::fake();
});

it('can create a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'doe',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201);

    $user = User::query()->where('email', 'john@example.com')->first();

    expect($user)
        ->not->toBeNull()
        ->and($user->username)->toBe('doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, EmailVerificationNotification::class);
});

it('validates required fields when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['username', 'email', 'password']);
});

it('validates username format when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'invalid Username-',
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['username']);
});

it('validates email format when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'doe',
        'email' => 'not-an-email',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates unique email when creating a user', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'doe',
        'email' => 'existing@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates unique username when creating a user', function () {
    User::factory()->create(['username' => 'existinguser']);

    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'doe',
        'email' => 'john@example.com',
        'username' => 'existinguser',
        'password' => 'password123',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['username']);
});

it('validates minimum password length when creating a user', function () {
    $response = $this->postJson(action([UserController::class, 'store']), [
        'username' => 'doe',
        'email' => 'john@example.com',
        'password' => 'short',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['password']);
});

it('can show a user profile', function () {
    $user = User::factory()->create([
        'username' => 'doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(action([UserController::class, 'show'], $user));

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'username' => 'doe',
        'email' => 'john@example.com',
        'posts_count' => 0,
        'followers_count' => 0,
        'following_count' => 0,
    ]);
});

it('can update user profile', function () {
    $user = User::factory()->create([
        'username' => 'doe',
        'email' => 'john@example.com',
        'email_verified_at' => now(),
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'username' => 'doe',
        'email' => 'jane@example.com',
    ]);

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'username' => 'doe',
        'email' => 'jane@example.com',
    ]);

    $user->refresh();
    expect($user->username)->toBe('doe')
        ->and($user->email)->toBe('jane@example.com')
        ->and($user->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, EmailVerificationNotification::class);
});

it('can partially update user profile', function () {
    $user = User::factory()->create([
        'username' => 'doe',
        'email' => 'john@example.com',
        'email_verified_at' => now(),
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'username' => 'doe',
    ]);

    $response->assertOk();
    $response->assertJson([
        'id' => $user->id,
        'username' => 'doe',
        'email' => 'john@example.com',
    ]);

    $user->refresh();
    expect($user->username)->toBe('doe')
        ->and($user->email)->toBe('john@example.com')
        ->and($user->email_verified_at)->not->toBeNull();

    Notification::assertNothingSent();
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
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'email_verified_at' => now(),
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'username' => 'doe',
        'email' => 'john@example.com',
    ]);

    $response->assertOk();

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();

    Notification::assertNothingSent();
});

it('validates username format when updating user profile', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'username' => 'Invalid Username-',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['username']);
});

it('requires authentication to show user profile', function () {
    $user = User::factory()->create();

    $response = $this->getJson(action([UserController::class, 'show'], $user));

    $response->assertStatus(401);
});

it('requires authentication to update user profile', function () {
    $user = User::factory()->create();

    $response = $this->putJson(action([UserController::class, 'update'], $user), [
        'username' => 'doe',
    ]);

    $response->assertStatus(401);
});

it('cannot update another users profile', function () {
    $user = User::factory()->create(['username' => 'doe']);
    $otherUser = User::factory()->create(['username' => 'other']);

    Sanctum::actingAs($user, ['*']);

    $response = $this->putJson(action([UserController::class, 'update'], $otherUser), [
        'username' => 'hacked',
    ]);

    $response->assertStatus(403);

    $otherUser->refresh();
    expect($otherUser->username)->toBe('other');
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
