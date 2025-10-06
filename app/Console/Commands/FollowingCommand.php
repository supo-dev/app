<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Support\Keyboard;
use App\Queries\FollowingFeedQuery;
use App\Support\KeyHandler;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;

use function Laravel\Prompts\text;
use function Termwind\render;

final class FollowingCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:following';

    public function handle(#[CurrentUser] $user, FollowingFeedQuery $query, KeyHandler $handler): void
    {
        $posts = $query->builder()
            ->limit(5)
            ->cursor();

        render(view('following.index', [
            'user' => $user,
            'posts' => $posts,
        ])->render());
    }
}
