<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use App\Queries\FollowingFeed;
use Illuminate\Database\Eloquent\Builder;

it('may return a query builder', function (): void {
    $user = User::factory()->create();
    $followingFeed = new FollowingFeed($user);

    $builder = $followingFeed->builder();

    expect($builder)->toBeInstanceOf(Builder::class);
});

it('may return posts from followed users by updated date', function (): void {
    $user = User::factory()->create();
    $followedUser = User::factory()->create();
    $otherUser = User::factory()->create();

    $user->following()->attach($followedUser->id);

    $followedPostOlder = Post::factory()->create([
        'user_id' => $followedUser->id,
        'updated_at' => now()->subMinutes(10),
    ]);
    $followedPostNewer = Post::factory()->create([
        'user_id' => $followedUser->id,
        'updated_at' => now(),
    ]);

    // post from a user not followed
    Post::factory()->create(['user_id' => $otherUser->id]);

    $followingFeed = new FollowingFeed($user);
    $posts = $followingFeed->builder()->get();

    expect($posts)->toHaveCount(2)
        ->and($posts->pluck('id')->all())->toMatchArray([$followedPostNewer->id, $followedPostOlder->id]);
});

it('may return empty collection when user follows no one', function (): void {
    $user = User::factory()->create();
    Post::factory()->create();

    $followingFeed = new FollowingFeed($user);
    $posts = $followingFeed->builder()->get();

    expect($posts)->toHaveCount(0);
});
