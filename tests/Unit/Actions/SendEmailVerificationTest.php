<?php

declare(strict_types=1);

use App\Actions\SendEmailVerification;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Support\EmailVerificationTokenManager;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Notification::fake();
});

it('may send email verification', function (): void {
    $user = User::factory()->create();
    $action = app(SendEmailVerification::class);

    $action->handle($user);

    $tokenManager = app(EmailVerificationTokenManager::class);
    expect($tokenManager->exists($user))->toBeTrue();

    Notification::assertSentTo($user, EmailVerificationNotification::class);
});

it('may generate different tokens for different users', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $action = app(SendEmailVerification::class);

    $action->handle($user1);
    $action->handle($user2);

    $tokenManager = app(EmailVerificationTokenManager::class);
    expect($tokenManager->exists($user1))->toBeTrue()
        ->and($tokenManager->exists($user2))->toBeTrue();

    Notification::assertSentTo($user1, EmailVerificationNotification::class);
    Notification::assertSentTo($user2, EmailVerificationNotification::class);
});

it('may overwrite existing token for same user', function (): void {
    $user = User::factory()->create();
    $action = app(SendEmailVerification::class);
    $tokenManager = app(EmailVerificationTokenManager::class);

    $action->handle($user);
    expect($tokenManager->exists($user))->toBeTrue();

    $firstToken = $tokenManager->generate($user);
    $action->handle($user);
    $secondToken = $tokenManager->generate($user);

    expect($tokenManager->exists($user))->toBeTrue()
        ->and($firstToken)->not->toBe($secondToken);

    Notification::assertSentToTimes($user, EmailVerificationNotification::class, 2);
});

it('may send notification with correct token', function (): void {
    $user = User::factory()->create();
    $action = app(SendEmailVerification::class);

    $action->handle($user);

    Notification::assertSentTo($user, EmailVerificationNotification::class, function (EmailVerificationNotification $notification) use ($user) {
        $tokenManager = app(EmailVerificationTokenManager::class);
        $notificationData = $notification->toArray($user);
        $mailable = $notification->toMail($user);

        return isset($notificationData['token']) &&
               mb_strlen($notificationData['token']) === 8 &&
               $tokenManager->verify($user, $notificationData['token'])
               && str_contains($mailable->greeting, $user->username);
    });
});
