<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final readonly class EmailVerificationTokenManager
{
    public const int TOKEN_LENGTH = 8;

    private const int TOKEN_TTL_MINUTES = 60;

    private const string CACHE_KEY_PREFIX = 'email_verification_token:';

    public function generate(User $user): string
    {
        $token = $this->createRandomToken();
        $this->store($user, $token);

        return $token;
    }

    public function verify(
        User $user,
        string $token
    ): bool {
        $cachedToken = $this->retrieve($user);

        if ($cachedToken === null || $cachedToken !== $token) {
            return false;
        }

        $this->forget($user);

        return true;
    }

    public function exists(User $user): bool
    {
        return $this->retrieve($user) !== null;
    }

    public function forget(User $user): void
    {
        Cache::forget($this->getCacheKey($user));
    }

    private function store(
        User $user,
        string $token
    ): void {
        $cacheKey = $this->getCacheKey($user);
        Cache::put($cacheKey, $token, now()->addMinutes(self::TOKEN_TTL_MINUTES));
    }

    private function retrieve(User $user): ?string
    {
        $token = Cache::get($this->getCacheKey($user));

        return is_string($token) ? $token : null;
    }

    private function createRandomToken(): string
    {
        return Str::random(self::TOKEN_LENGTH);
    }

    private function getCacheKey(User $user): string
    {
        return self::CACHE_KEY_PREFIX.$user->id;
    }
}
