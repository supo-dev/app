<?php

declare(strict_types=1);

use App\Http\Controllers\EmailVerificationController;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Support\EmailVerificationTokenManager;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Notification::fake();
});

it('can send email verification', function (): void {
    $user = User::factory()->create();

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(action([EmailVerificationController::class, 'send']));

    $response->assertStatus(200);

    $tokenManager = app(EmailVerificationTokenManager::class);
    expect($tokenManager->exists($user))->toBeTrue();

    Notification::assertSentTo($user, EmailVerificationNotification::class);
});

it('requires authentication to send verification', function (): void {
    $response = $this->postJson(action([EmailVerificationController::class, 'send']));

    $response->assertStatus(401);
});

it('can verify email with valid token', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $tokenManager = app(EmailVerificationTokenManager::class);
    $token = $tokenManager->generate($user);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(action([EmailVerificationController::class, 'verify']), [
        'token' => $token,
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'message',
        'user' => [
            'id',
            'username',
            'email',
            'email_verified',
        ],
    ]);
    $response->assertJson([
        'user' => [
            'email_verified' => true,
        ],
    ]);

    expect($user->fresh()->email_verified_at)->not->toBeNull();
});

it('rejects invalid token for verification', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(action([EmailVerificationController::class, 'verify']), [
        'token' => 'INVALID1',
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'message' => 'The provided token is invalid or has expired.',
        'errors' => [
            'token' => ['The provided token is invalid or has expired.'],
        ],
    ]);
});

it('requires authentication to verify email', function (): void {
    $response = $this->postJson(action([EmailVerificationController::class, 'verify']), [
        'token' => 'ABCD1234',
    ]);

    $response->assertStatus(401);
});

it('validates token format when verifying', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);

    Sanctum::actingAs($user, ['*']);

    $response = $this->postJson(action([EmailVerificationController::class, 'verify']), [
        'token' => 'short',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['token']);
});
