<?php

declare(strict_types=1);

use App\Actions\LoginUser;
use App\Models\User;
use Illuminate\Validation\ValidationException;

it('may login a user with correct credentials', function (): void {
    $user = User::factory()->create([
        'username' => 'johndoe',
        'password' => bcrypt('password123'),
    ]);
    $action = app(LoginUser::class);

    $result = $action->handle('johndoe', 'password123');

    expect($result)->toBeArray()
        ->and($result['user']->id)->toBe($user->id);
});

it('throws exception for invalid username', function (): void {
    $action = app(LoginUser::class);

    expect(fn () => $action->handle('invaliduser', 'password123'))
        ->toThrow(ValidationException::class);
});

it('throws exception for invalid password', function (): void {
    User::factory()->create([
        'username' => 'johndoe',
        'password' => bcrypt('correct-password'),
    ]);
    $action = app(LoginUser::class);

    expect(fn () => $action->handle('johndoe', 'wrong-password'))
        ->toThrow(ValidationException::class);
});
