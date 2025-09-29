<?php

declare(strict_types=1);

use App\Actions\CreateUserAction;
use App\DataObjects\CreateUserData;
use App\Models\User;

describe('create user action', function (): void {

    test('it creates a new user', function (): void {

        // Arrange
        $action = app(CreateUserAction::class);

        // Act
        $user = $action->handle(new CreateUserData(
            nickname: 'fbarrento',
            name: 'francisco barrento',
            email: 'francisco@example.com',
            password: 'password',
        ));

        expect($user)
            ->toBeInstanceOf(User::class)
            ->and($user->name)
            ->toBe('francisco barrento')
            ->and($user->email)
            ->toBe('francisco@example.com')
            ->and($user->nickname)
            ->toBe('fbarrento');

    });

});
