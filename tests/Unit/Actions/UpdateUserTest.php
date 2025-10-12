<?php

declare(strict_types=1);

use App\Actions\UpdateUser;
use App\Models\User;

it('may update username', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'new-username');

    expect($updatedUser->username)->toBe('new-username');
});

it('does not update when no data provided', function (): void {
    $user = User::factory()->create([
        'username' => 'original',
    ]);

    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user);

    expect($updatedUser->username)->toBe('original');
});

it('may update bio', function (): void {
    $user = User::factory()->create([
        'username' => 'username',
        'bio' => 'Original bio',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, bio: 'New bio text');

    expect($updatedUser->bio)->toBe('New bio text');
});

it('may update username and bio together', function (): void {
    $user = User::factory()->create([
        'username' => 'olduser',
        'bio' => 'Old bio',
    ]);
    $action = app(UpdateUser::class);

    $updatedUser = $action->handle($user, 'newuser', 'New bio');

    expect($updatedUser->username)->toBe('newuser')
        ->and($updatedUser->bio)->toBe('New bio');
});
