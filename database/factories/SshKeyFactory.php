<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SshKey;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SshKey>
 */
final class SshKeyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $publicKey = 'ssh-rsa '.base64_encode(random_bytes(256)).' '.$this->faker->userName().'@'.$this->faker->domainName();
        $fingerprint = 'SHA256:'.base64_encode(hash('sha256', $publicKey, true));

        return [
            'user_id' => User::factory(),
            'public_key' => $publicKey,
            'fingerprint' => $fingerprint,
        ];
    }
}
