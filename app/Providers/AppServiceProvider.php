<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\SshSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SshSession::class, fn (): SshSession => $this->app->runningUnitTests()
            ? new SshSession('test-ssh-key')
            : SshSession::fromEnvironment());
    }

    public function boot(): void
    {
        Model::unguard();
    }
}
