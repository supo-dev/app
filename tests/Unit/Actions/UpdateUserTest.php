<?php

declare(strict_types=1);

use App\Actions\UpdateUser;
use App\Models\User;

it('may update user name', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'New Name');

    expect($updatedUser->name)->toBe('New Name')
        ->and($updatedUser->email)->toBe('original@example.com');
});

it('may update user email', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, null, 'new@example.com');

    expect($updatedUser->name)->toBe('Original Name')
        ->and($updatedUser->email)->toBe('new@example.com');
});

it('may update both name and email', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'New Name', 'new@example.com');

    expect($updatedUser->name)->toBe('New Name')
        ->and($updatedUser->email)->toBe('new@example.com');
});

it('does not update when no data provided', function (): void {
    $user = User::factory()->create([
        'name' => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user);

    expect($updatedUser->name)->toBe('Original Name')
        ->and($updatedUser->email)->toBe('original@example.com');
});
