<?php

declare(strict_types=1);

use App\Actions\CreateUserAction;
use App\Models\User;

test('it creates a new user', function (): void {

    // Arrange
    $action = app(CreateUserAction::class);

    // Act
    $user = $action->handle(
        nickname: 'fbarrento',
        name: 'francisco barrento',
        email: 'francisco@example.com',
        password: 'password',
    );

    expect($user)
        ->toBeInstanceOf(User::class)
        ->and($user->name)
        ->toBe('francisco barrento')
        ->and($user->email)
        ->toBe('francisco@example.com')
        ->and($user->nickname)
        ->toBe('fbarrento');

});
