<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Support\Keyboard;
use Closure;
use function Termwind\terminal;

final class KeyHandler
{
    /**
     * @var array<string, Closure>
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

    public function listen(): void
    {
        $this->enableRawMode();

        try {
            while (true) {
                $char = $this->readChar();

                if ($char === 'q') {
                    break;
                }

                $this->handle($char);
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

    private function readChar(): string
    {
        return fread(STDIN, 1);
    }
}
