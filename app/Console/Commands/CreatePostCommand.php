<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\CreatePost;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;

use function Laravel\Prompts\textarea;

final class CreatePostCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:create-post';

    public function handle(#[CurrentUser] User $user, CreatePost $action): void
    {
        $content = textarea(
            label: 'What do you want to share?',
            placeholder: 'What\'s happening?',
            required: true,
            validate: ['required', 'string', 'max:280'],
        );

        $action->handle($user, $content);
    }
}
