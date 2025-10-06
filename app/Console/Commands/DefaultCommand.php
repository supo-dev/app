<?php

declare(strict_types=1);

namespace App\Console\Commands;

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
    public function handle(): void
    {
        $this->call(SignInCommand::class);

        $this->call(FollowingCommand::class);
    }
}
