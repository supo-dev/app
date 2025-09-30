<?php

declare(strict_types=1);

use App\Actions\LoginUser;
use App\Models\User;
use Illuminate\Validation\ValidationException;

it('may login a user with correct credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
    ]);
    $action = app(LoginUser::class);

    $result = $action->handle('john@example.com', 'password123');

    expect($result)->toBeArray()
        ->and($result['user']->id)->toBe($user->id)
        ->and($result['token'])->toBeString()
        ->and(mb_strlen($result['token']))->toBeGreaterThan(0);
});

it('throws exception for invalid email', function (): void {
    $action = app(LoginUser::class);

    expect(fn () => $action->handle('invalid@example.com', 'password123'))
        ->toThrow(ValidationException::class);
});

it('throws exception for invalid password', function (): void {
    User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('correct-password'),
    ]);
    $action = app(LoginUser::class);

    expect(fn () => $action->handle('john@example.com', 'wrong-password'))
        ->toThrow(ValidationException::class);
});
