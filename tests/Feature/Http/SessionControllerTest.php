<?php

declare(strict_types=1);

use App\Http\Controllers\SessionController;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can login a user', function () {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson(action([SessionController::class, 'store']), [
        'email' => 'john@example.com',
        'password' => 'password123',
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'user' => [
            'id',
            'name',
            'email',
        ],
        'token',
    ]);
    $response->assertJson([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => 'john@example.com',
        ],
    ]);

    expect($response->json('token'))->toBeString();
});

it('validates login credentials', function () {
    $response = $this->postJson(action([SessionController::class, 'store']), [
        'email' => 'invalid@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});

it('validates required login fields', function () {
    $response = $this->postJson(action([SessionController::class, 'store']), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email', 'password']);
});

it('can check current session when authenticated', function () {
    $user = User::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
    ]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->getJson(action([SessionController::class, 'show']));

    $response->assertOk();
    $response->assertJson([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ],
    ]);
});

it('returns unauthenticated when not logged in', function () {
    $response = $this->getJson(action([SessionController::class, 'show']));

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Unauthenticated.',
    ]);
});

it('can logout a user', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->deleteJson(action([SessionController::class, 'destroy']));

    $response->assertStatus(204);

    // Verify token was deleted
    expect($user->tokens()->count())->toBe(0);
});

it('requires authentication to logout', function () {
    $response = $this->deleteJson(action([SessionController::class, 'destroy']));

    $response->assertStatus(401);
});
