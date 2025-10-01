<?php

declare(strict_types=1);

use App\Actions\VerifyEmail;
use App\Models\User;
use App\Support\EmailVerificationTokenManager;
use Illuminate\Validation\ValidationException;

it('may verify email with valid token', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $tokenManager = app(EmailVerificationTokenManager::class);
    $action = app(VerifyEmail::class);

    $token = $tokenManager->generate($user);
    $result = $action->handle($user, $token);

    expect($result)->toBeTrue()
        ->and($user->fresh()->email_verified_at)->not->toBeNull()
        ->and($tokenManager->exists($user))->toBeFalse();
});

it('may return true if email already verified', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $action = app(VerifyEmail::class);

    $result = $action->handle($user, 'any-token');

    expect($result)->toBeTrue()
        ->and($user->fresh()->email_verified_at)->not->toBeNull();
});

it('may throw exception for invalid token', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $action = app(VerifyEmail::class);

    expect(fn () => $action->handle($user, 'invalid-token'))
        ->toThrow(ValidationException::class);
});

it('may throw exception for expired token', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $tokenManager = app(EmailVerificationTokenManager::class);
    $action = app(VerifyEmail::class);

    $token = $tokenManager->generate($user);
    $tokenManager->forget($user);

    expect(fn () => $action->handle($user, $token))
        ->toThrow(ValidationException::class);
});

it('may verify email and update timestamp', function (): void {
    $user = User::factory()->create(['email_verified_at' => null]);
    $tokenManager = app(EmailVerificationTokenManager::class);
    $action = app(VerifyEmail::class);

    $token = $tokenManager->generate($user);
    $beforeTime = now()->subSecond();

    $result = $action->handle($user, $token);

    $afterTime = now()->addSecond();
    $user->refresh();

    expect($result)->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->email_verified_at->greaterThan($beforeTime))->toBeTrue()
        ->and($user->email_verified_at->lessThan($afterTime))->toBeTrue();
});
