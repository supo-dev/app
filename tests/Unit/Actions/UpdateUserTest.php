<?php

declare(strict_types=1);

use App\Actions\UpdateUser;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Notification::fake();
});

it('may update user name', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
        'email' => 'original@example.com',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'new-username');

    expect($updatedUser->username)->toBe('new-username')
        ->and($updatedUser->email)->toBe('original@example.com');
});

it('may update user email', function (): void {
    $user = User::factory()->create([
        'username' => 'original',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, null, 'new@example.com');

    expect($updatedUser->username)->toBe('original')
        ->and($updatedUser->email)->toBe('new@example.com')
        ->and($updatedUser->email_verified_at)->toBeNull();

    Notification::assertSentTo($updatedUser, EmailVerificationNotification::class);
});

it('may update both name and email', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'new-username', 'new@example.com');

    expect($updatedUser->username)->toBe('new-username')
        ->and($updatedUser->email)->toBe('new@example.com')
        ->and($updatedUser->email_verified_at)->toBeNull();

    Notification::assertSentTo($updatedUser, EmailVerificationNotification::class);
});

it('does not update when no data provided', function (): void {
    $user = User::factory()->create([
        'username' => 'original',
        'email' => 'original@example.com',
    ]);

    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user);

    expect($updatedUser->username)->toBe('original')
        ->and($updatedUser->email)->toBe('original@example.com');
});

it('does not send email verification when email is not changed', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'new-username', 'original@example.com');

    expect($updatedUser->username)->toBe('new-username')
        ->and($updatedUser->email)->toBe('original@example.com')
        ->and($updatedUser->email_verified_at)->not->toBeNull();

    Notification::assertNothingSent();
});

it('does not send email verification when only username is updated', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
        'email' => 'original@example.com',
        'email_verified_at' => now(),
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'new-username');

    expect($updatedUser->username)->toBe('new-username')
        ->and($updatedUser->email)->toBe('original@example.com')
        ->and($updatedUser->email_verified_at)->not->toBeNull();

    Notification::assertNothingSent();
});
