<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\CreatePost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\textarea;

final class CreatePostCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:create-post';

    public function handle(CreatePost $action): void
    {
        //
    }
}
