<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\CreatePost;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\textarea;

final class CreatePostCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:create-post';

    public function handle(#[CurrentUser] $user, CreatePost $action): void
    {
        $content = textarea(
            label: 'What do you want to share?',
            placeholder: 'Hello world!',
            required: true,
            validate: ['required', 'string', 'max:280'],
        );

        $action->handle($user, $content);

        $this->call(FollowingCommand::class);
    }
}
