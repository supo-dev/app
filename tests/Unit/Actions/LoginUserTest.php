<?php

declare(strict_types=1);

use App\Actions\LoginUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

it('may login a user with correct credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'john@example.com',
        'password' => bcrypt('password123'),
    ]);
    $action = app(LoginUser::class);

    $loggedInUser = $action->handle('john@example.com', 'password123');

    expect($loggedInUser->id)->toBe($user->id)
        ->and(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($user->id);
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
