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
