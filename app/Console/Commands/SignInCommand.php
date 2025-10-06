<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\SignInAction;
use App\Models\User;
use App\Support\SshSession;
use Illuminate\Console\Command;

final class SignInCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:sign-in';

    public function handle(SshSession $sshSession, SignInAction $signInAction): void
    {
        $user = User::bySshKey($sshSession->publicKey);

        if (! $user instanceof User) {
            $this->call(SignUpCommand::class);

            return;
        }

        $signInAction->handle($user);
    }
}
