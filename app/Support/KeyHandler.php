<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Support\Keyboard;
use Closure;

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
        while (true) {
            $input = trim(fgets(STDIN));

            if ($input === 'q') {
                break;
            }

            $this->handle($input);
        }
    }
}
