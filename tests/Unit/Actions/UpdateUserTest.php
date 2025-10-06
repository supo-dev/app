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
