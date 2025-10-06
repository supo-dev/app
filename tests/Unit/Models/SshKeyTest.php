<?php

declare(strict_types=1);

use App\Models\SshKey;
use App\Models\User;

test('to array', function () {
    $sshKey = SshKey::factory()->create()->fresh();

    expect(array_keys($sshKey->toArray()))
        ->toBe([
            'id',
            'user_id',
            'public_key',
            'fingerprint',
            'created_at',
            'updated_at',
        ]);
});

test('relation user', function () {
    $sshKey = SshKey::factory()->create()->fresh();

    expect($sshKey->user)
        ->toBeInstanceOf(User::class)
        ->and($sshKey->user_id)->toBe($sshKey->user->id);
});
