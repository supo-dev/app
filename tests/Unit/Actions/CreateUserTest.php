<?php

declare(strict_types=1);

use App\Actions\CreateUser;
use App\Models\User;

it('may create a user', function (): void {
    $name = 'John Doe';
    $email = 'john@example.com';
    $username = 'johndoe';
    $password = 'password123';
    $action = app(CreateUser::class);

    $user = $action->handle($name, $email, $username, $password);

    expect($user)->toBeInstanceOf(User::class)
        ->and($user->name)->toBe($name)
        ->and($user->email)->toBe($email);
});
