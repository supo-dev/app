<?php

declare(strict_types=1);

use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('belongs to a user', function (): void {
    $like = Like::factory()->create();

    expect($like->user)->toBeInstanceOf(User::class);
});

it('belongs to a post', function (): void {
    $like = Like::factory()->create();

    expect($like->post)->toBeInstanceOf(Post::class);
});
