<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Whisp\Server;

final class SshServerCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:ssh-server';

    public function handle(): void
    {
        $server = new Server(port: 2222);

        $server->run(base_path('servers/default.php'));
    }
}
