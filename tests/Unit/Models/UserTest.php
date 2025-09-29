<?php

declare(strict_types=1);

use App\Models\User;

test('to array', function (): void {

    /** @var User $user */
    $user = User::factory()
        ->createQuietly()
        ->fresh();

    expect(array_keys($user->toArray()))
        ->toBe([
            'id',
            'created_at',
            'updated_at',
            'nickname',
            'name',
            'email',
            'email_verified_at',
        ]);

});
