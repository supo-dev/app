<?php

declare(strict_types=1);

use App\Actions\CreateUser;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function (): void {
    Notification::fake();
});

it('may create a user', function (): void {
    $username = 'johndoe';
    $email = 'john@example.com';
    $password = 'password123';
    $action = app(CreateUser::class);

    $user = $action->handle($username, $email, $password);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->username)->toBe($username)
        ->and($user->email)->toBe($email)
        ->and($user->email_verified_at)->toBeNull();
});

it('may send email verification on user creation', function (): void {
    $username = 'johndoe';
    $email = 'john@example.com';
    $password = 'password123';
    $action = app(CreateUser::class);

    $user = $action->handle($username, $email, $password);

    Notification::assertSentTo($user, EmailVerificationNotification::class);
});
