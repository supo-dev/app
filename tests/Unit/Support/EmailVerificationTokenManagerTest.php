<?php

declare(strict_types=1);

use App\Models\User;
use App\Support\EmailVerificationTokenManager;

it('may generate a token', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $token = $tokenManager->generate($user);

    expect($token)->toBeString()
        ->and(mb_strlen($token))->toBe(8)
        ->and($tokenManager->exists($user))->toBeTrue();
});

it('may verify a valid token', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $token = $tokenManager->generate($user);
    $result = $tokenManager->verify($user, $token);

    expect($result)->toBeTrue()
        ->and($tokenManager->exists($user))->toBeFalse();
});

it('may return false for invalid token', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $tokenManager->generate($user);
    $result = $tokenManager->verify($user, 'invalid');

    expect($result)->toBeFalse()
        ->and($tokenManager->exists($user))->toBeTrue();
});

it('may return false for non-existent token', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $result = $tokenManager->verify($user, 'nonexist');

    expect($result)->toBeFalse()
        ->and($tokenManager->exists($user))->toBeFalse();
});

it('may check if token exists', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    expect($tokenManager->exists($user))->toBeFalse();

    $tokenManager->generate($user);

    expect($tokenManager->exists($user))->toBeTrue();
});

it('may forget a token', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $tokenManager->generate($user);
    expect($tokenManager->exists($user))->toBeTrue();

    $tokenManager->forget($user);

    expect($tokenManager->exists($user))->toBeFalse();
});

it('may handle different users independently', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $token1 = $tokenManager->generate($user1);
    $token2 = $tokenManager->generate($user2);

    expect($token1)->not->toBe($token2)
        ->and($tokenManager->exists($user1))->toBeTrue()
        ->and($tokenManager->exists($user2))->toBeTrue()
        ->and($tokenManager->verify($user1, $token1))->toBeTrue()
        ->and($tokenManager->exists($user1))->toBeFalse()
        ->and($tokenManager->exists($user2))->toBeTrue();
});

it('may generate multiple tokens for same user overwriting previous', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $token1 = $tokenManager->generate($user);
    $token2 = $tokenManager->generate($user);

    expect($token1)->not->toBe($token2)
        ->and($tokenManager->verify($user, $token1))->toBeFalse()
        ->and($tokenManager->verify($user, $token2))->toBeTrue();
});

it('may generate tokens with correct length', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $tokens = [];
    for ($i = 0; $i < 10; $i++) {
        $tokens[] = $tokenManager->generate($user);
    }

    foreach ($tokens as $token) {
        expect(mb_strlen($token))->toBe(8);
    }
});

it('may generate unique tokens', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $tokens = [];
    for ($i = 0; $i < 100; $i++) {
        $tokens[] = $tokenManager->generate($user);
    }

    $uniqueTokens = array_unique($tokens);
    expect(count($uniqueTokens))->toBeGreaterThan(90);
});

it('may handle empty token verification', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $result = $tokenManager->verify($user, '');

    expect($result)->toBeFalse();
});

it('may handle whitespace token verification', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $tokenManager->generate($user);
    $result = $tokenManager->verify($user, '   ');

    expect($result)->toBeFalse()
        ->and($tokenManager->exists($user))->toBeTrue();
});

it('may forget non-existent token without error', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    expect($tokenManager->exists($user))->toBeFalse();

    $tokenManager->forget($user);

    expect($tokenManager->exists($user))->toBeFalse();
});

it('may handle case sensitive token verification', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    $token = $tokenManager->generate($user);
    $upperToken = mb_strtoupper($token);
    $lowerToken = mb_strtolower($token);

    if ($token !== $upperToken) {
        expect($tokenManager->verify($user, $upperToken))->toBeFalse();
    }
    if ($token !== $lowerToken) {
        expect($tokenManager->verify($user, $lowerToken))->toBeFalse();
    }

    expect($tokenManager->verify($user, $token))->toBeTrue();
});

it('may generate tokens containing only valid characters', function (): void {
    $user = User::factory()->create();
    $tokenManager = app(EmailVerificationTokenManager::class);

    for ($i = 0; $i < 50; $i++) {
        $token = $tokenManager->generate($user);
        expect($token)->toMatch('/^[a-zA-Z0-9]+$/');
    }
});
