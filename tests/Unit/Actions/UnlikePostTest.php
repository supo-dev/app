<?php

declare(strict_types=1);

use App\Actions\UnlikePost;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;

it('may unlike a post', function (): void {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    // Create a like first
    $like = Like::factory()->create([
        'user_id' => $user->id,
        'post_id' => $post->id,
    ]);

    $action = app(UnlikePost::class);

    $action->handle($user, $post);

    // Check that the like was deleted
    expect(Like::where('user_id', $user->id)->where('post_id', $post->id)->exists())->toBeFalse();
});
