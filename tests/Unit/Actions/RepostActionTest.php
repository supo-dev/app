<?php

declare(strict_types=1);

use App\Actions\RepostAction;
use App\Models\Post;
use App\Models\Repost;
use App\Models\User;

it('can repost a post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $action = new RepostAction;

    expect(Repost::count())->toBe(0);

    $action->handle($post, $user);

    expect(Repost::count())->toBe(1);

    $repost = Repost::first();
    expect($repost->user_id)->toBe($user->id);
    expect($repost->post_id)->toBe($post->id);
});

it('does not create duplicate reposts', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();
    $action = new RepostAction;

    $action->handle($post, $user);
    $action->handle($post, $user); // Second repost

    expect(Repost::count())->toBe(1);
});

it('allows different users to repost the same post', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $post = Post::factory()->create();
    $action = new RepostAction;

    $action->handle($post, $user1);
    $action->handle($post, $user2);

    expect(Repost::count())->toBe(2);
});
