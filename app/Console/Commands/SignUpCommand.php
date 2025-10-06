<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\SignUpAction;
use App\Rules\UserUsername;
use App\Support\SshSession;
use Illuminate\Console\Command;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

final class SignUpCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:sign-up';

    public function handle(SshSession $sshSession, SignUpAction $action): void
    {
        $username = text(
            label: 'Enter your desired username',
            placeholder: 'johndoe',
            required: true,
            validate: ['required', 'string', new UserUsername()],
        );

        $password = $this->password();
        $publicKey = $sshSession->publicKey;

        $action->handle($username, $password, $publicKey);

        $this->call(SignInCommand::class);
    }

    private function password(): string
    {
        $password = password(
            label: 'Please enter your desired password',
            required: true,
            validate: ['required', 'string', 'min:8'],
        );

        $confirmPassword = password(
            label: 'Please confirm your password',
            required: true,
        );

        if ($password !== $confirmPassword) {
            $this->components->error('Passwords do not match. Please try again.');

            return $this->password();
        }

        return $password;
    }
}
