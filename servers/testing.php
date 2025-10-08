<?php

declare(strict_types=1);

use App\Console\Commands\TestingCommand;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Foundation\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

define('LARAVEL_START', microtime(true));

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Set non-interactive mode for testing
putenv('SUPO_NON_INTERACTIVE=true');
$_ENV['SUPO_NON_INTERACTIVE'] = true;

// Bootstrap Laravel and handle the command...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(ConsoleKernelContract::class);

$input = new ArgvInput();

$status = $kernel->call(TestingCommand::class, [
    'command-class' => $input->getFirstArgument(),
], new ConsoleOutput());

$kernel->terminate($input, $status);

exit($status);
