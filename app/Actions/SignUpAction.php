<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\SshKey;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SensitiveParameter;

final readonly class SignUpAction
{
    public function __construct(
        private CreateUser $createUser,
        private CreateSshKey $createSshKey
    ) {}

    /**
     * @return array{user: User, sshKey: SshKey}
     */
    public function handle(
        string $username,
        #[SensitiveParameter] string $password,
        string $publicKey
    ): array {
        return DB::transaction(function () use ($username, $password, $publicKey): array {
            $user = $this->createUser->handle($username, $password);
            $sshKey = $this->createSshKey->handle($user, $publicKey);

            return [
                'user' => $user,
                'sshKey' => $sshKey,
            ];
        });
    }
}
