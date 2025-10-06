<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Support\Keyboard;
use Closure;
use function Termwind\terminal;

final class KeyHandler
{
    /**
     * @var array<Keyboard, Closure>
     */
    private array $handlers = [];

    public function on(Keyboard $key, Closure $handler): self
    {
        $this->handlers[$key->value] = $handler;

        return $this;
    }

    public function handle(string $input): mixed
    {
        if (! isset($this->handlers[$input])) {
            return null;
        }

        return $this->handlers[$input]();
    }

    public function hasHandler(Keyboard $key): bool
    {
        return isset($this->handlers[$key->value]);
    }

    public function listen(?Closure $onRefresh = null, int $refreshInterval = 5): void
    {
        $this->enableRawMode();
        $lastRefresh = time();

        try {
            while (true) {
                $char = $this->readCharNonBlocking();

                if ($char === 'q') {
                    break;
                }

                if ($char !== '') {
                    terminal()->clear(); // @phpstan-ignore-line

                    $this->handle($char);
                    $lastRefresh = time();
                }

                if ($onRefresh instanceof Closure && (time() - $lastRefresh) >= $refreshInterval) {
                    terminal()->clear(); // @phpstan-ignore-line

                    $onRefresh();
                    $lastRefresh = time();
                }

                time_nanosleep(0, 100000000);
            }
        } finally {
            $this->disableRawMode();
        }
    }

    private function enableRawMode(): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            system('stty -icanon -echo');
        }
    }

    private function disableRawMode(): void
    {
        if (PHP_OS_FAMILY !== 'Windows') {
            system('stty icanon echo');
        }
    }

    private function readCharNonBlocking(): string
    {
        stream_set_blocking(STDIN, false);
        $char = (string) fread(STDIN, 1);
        stream_set_blocking(STDIN, true);

        return $char;
    }
}
