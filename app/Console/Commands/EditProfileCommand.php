<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\UpdateUser;
use App\Models\User;
use App\Rules\UserUsername;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;

use function Laravel\Prompts\info;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;

final class EditProfileCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:edit-profile';

    public function handle(#[CurrentUser] User $user, UpdateUser $action): void
    {
        info('Edit your profile');

        $username = text(
            label: 'Username',
            placeholder: 'Enter your username',
            default: $user->username,
            required: true,
            validate: ['required', 'string', 'max:255', new UserUsername($user)],
        );

        $bio = textarea(
            label: 'Bio',
            placeholder: 'Tell us about yourself...',
            default: $user->bio ?? '',
            validate: ['nullable', 'string', 'max:500'],
        );

        $action->handle($user, $username, $bio);

        info('Profile updated successfully!');
    }
}
