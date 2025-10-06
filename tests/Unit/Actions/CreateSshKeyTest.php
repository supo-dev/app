<?php

declare(strict_types=1);

use App\Actions\CreateSshKey;
use App\Models\SshKey;
use App\Models\User;

it('may create an ssh key for a user', function (): void {
    $user = User::factory()->create();
    $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC... user@example.com';
    $action = app(CreateSshKey::class);

    $sshKey = $action->handle($user, $publicKey);

    expect($sshKey)->toBeInstanceOf(SshKey::class)
        ->and($sshKey->user_id)->toBe($user->id)
        ->and($sshKey->public_key)->toBe($publicKey);
});

it('stores ssh key in database', function (): void {
    $user = User::factory()->create();
    $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC... user@example.com';
    $action = app(CreateSshKey::class);

    $sshKey = $action->handle($user, $publicKey);

    expect(SshKey::query()->where('id', $sshKey->id)->exists())->toBeTrue()
        ->and(SshKey::query()->where('public_key', $publicKey)->exists())->toBeTrue();
});
