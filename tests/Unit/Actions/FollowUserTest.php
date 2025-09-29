<?php

declare(strict_types=1);

use App\Actions\FollowUser;
use App\Models\User;

it('may follow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $action = app(FollowUser::class);

    $action->handle($user, $targetUser);

    expect($user->following()->count())->toBe(1)
        ->and($targetUser->followers()->count())->toBe(1);
});

test('re-following a user does not duplicate the follow', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $action = app(FollowUser::class);

    $action->handle($user, $targetUser);

    expect($user->following()->count())->toBe(1)
        ->and($targetUser->followers()->count())->toBe(1);

    $action->handle($user, $targetUser);

    expect($user->following()->count())->toBe(1)
        ->and($targetUser->followers()->count())->toBe(1);
});
