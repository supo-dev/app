<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

final class FollowingCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:following';

    public function handle(): void
    {
        $this->info('Following command executed.');
    }
}
