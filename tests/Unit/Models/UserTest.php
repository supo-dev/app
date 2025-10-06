<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'username',
            'created_at',
            'updated_at',
        ]);
});

test('relation posts', function () {
    $user = User::factory()->hasPosts(3)->create()->fresh();

    expect($user->posts)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Post::class);
});

test('relation following', function () {
    $userA = User::factory()->create()->fresh();
    $userB = User::factory()->create()->fresh();

    $userA->following()->attach($userB);

    expect($userA->following)
        ->toHaveCount(1)
        ->each->toBeInstanceOf(User::class)
        ->and($userA->following->first()->id)->toBe($userB->id);
});

test('relation followers', function () {
    $userA = User::factory()->create()->fresh();
    $userB = User::factory()->create()->fresh();

    $userB->following()->attach($userA);

    expect($userA->followers)
        ->toHaveCount(1)
        ->each->toBeInstanceOf(User::class)
        ->and($userA->followers->first()->id)->toBe($userB->id);
});

test('relation ssh keys', function () {
    $user = User::factory()->hasSshKeys(3)->create()->fresh();

    expect($user->sshKeys)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(App\Models\SshKey::class);
});
