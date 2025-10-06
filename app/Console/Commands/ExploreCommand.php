<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Queries\ForYouFeedQuery;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;

use function Termwind\render;

final class ExploreCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:explore';

    public function handle(#[CurrentUser] User $user, ForYouFeedQuery $query): void
    {
        $posts = $query->builder()
            ->limit(5)
            ->cursor();

        render(view('explore.index', [
            'user' => $user,
            'posts' => $posts,
        ])->render());
    }
}
