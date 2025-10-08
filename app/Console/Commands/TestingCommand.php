<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\SignInAction;
use App\Models\User;
use Illuminate\Console\Command;

final class TestingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:testing {command-class : The command class to run}';

    /**
     * Execute the console command.
     */
    public function handle(SignInAction $action): void
    {
        $user = User::query()->firstOrCreate(
            ['username' => 'claude'],
            [
                'password' => bcrypt('password'),
            ],
        );

        $action->handle($user);

        $command = $this->argument('command-class');

        $this->call($command);
    }
}
