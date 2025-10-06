<?php

declare(strict_types=1);

use App\Actions\SignUpAction;
use App\Models\SshKey;
use App\Models\User;

it('may sign up a user with ssh key', function (): void {
    $username = 'johndoe';
    $password = 'password123';
    $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC... user@example.com';
    $action = app(SignUpAction::class);

    $result = $action->handle($username, $password, $publicKey);

    expect($result)->toBeArray()
        ->and($result)->toHaveKeys(['user', 'sshKey'])
        ->and($result['user'])->toBeInstanceOf(User::class)
        ->and($result['user']->username)->toBe($username)
        ->and($result['sshKey'])->toBeInstanceOf(SshKey::class)
        ->and($result['sshKey']->user_id)->toBe($result['user']->id)
        ->and($result['sshKey']->public_key)->toBe($publicKey);
});

it('creates user and ssh key in database', function (): void {
    $username = 'johndoe';
    $password = 'password123';
    $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC... user@example.com';
    $action = app(SignUpAction::class);

    $result = $action->handle($username, $password, $publicKey);

    expect(User::query()->where('username', $username)->exists())->toBeTrue()
        ->and(SshKey::query()->where('user_id', $result['user']->id)->exists())->toBeTrue();
});

it('associates ssh key with created user', function (): void {
    $username = 'johndoe';
    $password = 'password123';
    $publicKey = 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQC... user@example.com';
    $action = app(SignUpAction::class);

    $result = $action->handle($username, $password, $publicKey);

    expect($result['user']->sshKeys)->toHaveCount(1)
        ->and($result['user']->sshKeys->first()->id)->toBe($result['sshKey']->id);
});
