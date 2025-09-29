<?php

declare(strict_types=1);

use App\Actions\UnfollowUser;
use App\Models\User;

it('may unfollow a user', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $user->following()->attach($targetUser->id);
    $action = app(UnfollowUser::class);

    $action->handle($user, $targetUser);

    expect($user->following()->count())->toBe(0);
});

test('re-unfollowing does nothing', function (): void {
    $user = User::factory()->create();
    $targetUser = User::factory()->create();
    $action = app(UnfollowUser::class);

    expect($user->following()->count())->toBe(0);

    $action->handle($user, $targetUser);

    expect($user->following()->count())->toBe(0);
});
