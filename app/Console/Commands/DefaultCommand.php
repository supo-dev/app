<?php

declare(strict_types=1);

namespace App\Console\Commands;

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

        $this->call(FeedCommand::class);
    }
}
