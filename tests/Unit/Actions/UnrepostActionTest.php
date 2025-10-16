<?php

declare(strict_types=1);

use App\Actions\UnrepostAction;
use App\Models\Post;
use App\Models\Repost;
use App\Models\User;

it('can remove a repost', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    Repost::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    expect(Repost::count())->toBe(1);

    $action = new UnrepostAction;
    $action->handle($post, $user);

    expect(Repost::count())->toBe(0);
});

it('does nothing when removing non-existent repost', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $action = new UnrepostAction;

    expect(Repost::count())->toBe(0);

    $action->handle($post, $user);

    expect(Repost::count())->toBe(0);
});

it('only removes the specific user repost', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $post = Post::factory()->create();

    // From both users
    Repost::factory()->create([
        'user_id' => $user1->id,
        'post_id' => $post->id,
    ]);

    Repost::factory()->create([
        'user_id' => $user2->id,
        'post_id' => $post->id,
    ]);

    expect(Repost::count())->toBe(2);

    $action = new UnrepostAction;
    $action->handle($post, $user1);

    expect(Repost::count())->toBe(1);
    expect(Repost::first()->user_id)->toBe($user2->id);
});
