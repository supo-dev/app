<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'name',
            'email',
            'email_verified_at',
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
