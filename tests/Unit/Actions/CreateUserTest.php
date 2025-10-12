<?php

declare(strict_types=1);

use App\Actions\CreateUser;
use App\Models\User;

it('may create a user', function (): void {
    $username = 'johndoe';
    $password = 'password123';
    $action = app(CreateUser::class);

    $user = $action->handle($username, $password);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->username)->toBe($username);
});

it('may create a user with bio', function (): void {
    $username = 'johndoe';
    $password = 'password123';
    $bio = 'This is my bio';
    $action = app(CreateUser::class);

    $user = $action->handle($username, $password, $bio);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->username)->toBe($username)
        ->and($user->bio)->toBe($bio);
});
