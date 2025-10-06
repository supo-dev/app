<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final readonly class SshSession
{
    public function __construct(public string $publicKey)
    {
        //
    }

    public static function fromEnvironment(): self
    {
        $key = $_ENV['WHISP_USER_PUBLIC_KEY'];

        throw_unless(is_string($key), new RuntimeException('WHISP_USER_PUBLIC_KEY environment variable must be a string'));

        return new self($key);
    }
}
