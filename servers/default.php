<?php

declare(strict_types=1);

use App\Console\Commands\DefaultCommand;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the command...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(ConsoleKernelContract::class);

$status = $kernel->call(DefaultCommand::class, [], new ConsoleOutput());

$kernel->terminate(new ArgvInput(), $status);

exit($status);
