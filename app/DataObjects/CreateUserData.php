<?php

declare(strict_types=1);

namespace App\DataObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Hash;

/**
 * @implements Arrayable<string, string>
 */
final readonly class CreateUserData implements Arrayable
{
    public function __construct(
        public string $nickname,
        public string $name,
        public string $email,
        public string $password,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'nickname' => $this->nickname,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
    }
}
