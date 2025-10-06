<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Support\Keyboard;
use App\Support\KeyHandler;
use Illuminate\Console\Command;

final class DefaultCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:default';

    /**
     * Execute the console command.
     */
    public function handle(KeyHandler $handler): void
    {
        $this->call(SignInCommand::class);

        $currentCommand = 'app:following';

        $handler
            ->on(Keyboard::F, function () use (&$currentCommand): void {
                $currentCommand = 'app:following';
                $this->call(FollowingCommand::class);
            })
            ->on(Keyboard::E, function () use (&$currentCommand): void {
                $currentCommand = 'app:explore';
                $this->call(ExploreCommand::class);
            })
            ->on(Keyboard::P, fn () => $this->call(CreatePostCommand::class));

        $this->call(FollowingCommand::class);

        $handler->listen(
            onRefresh: function () use (&$currentCommand): void {
                $this->call($currentCommand);
            },
            refreshInterval: 5
        );
    }
}
