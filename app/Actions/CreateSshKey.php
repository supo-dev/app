<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\SshKey;
use App\Models\User;

final readonly class CreateSshKey
{
    public function handle(User $user, string $publicKey): SshKey
    {
        return SshKey::query()->create([
            'user_id' => $user->id,
            'public_key' => $publicKey,
        ]);
    }
}
