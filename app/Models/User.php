<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property-read int $id
 * @property-read string $username
 * @property-read string $password
 * @property-read string|null $bio
 * @property-read string|null $remember_token
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, Post> $posts
 * @property-read Collection<int, Like> $likes
 * @property-read Collection<int, User> $followers
 * @property-read Collection<int, User> $following
 * @property-read Collection<int, SshKey> $sshKeys
 */
final class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function bySshKey(string $sshKey): ?self
    {
        return self::query()->whereHas('sshKeys', function (Builder $query) use ($sshKey): void {
            $query->where('public_key', $sshKey);
        })->first();
    }

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'username' => 'string',
            'password' => 'hashed',
            'bio' => 'string',
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany<Like, $this>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'followers', 'user_id', 'follower_id');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * @return HasMany<SshKey, $this>
     */
    public function sshKeys(): HasMany
    {
        return $this->hasMany(SshKey::class);
    }
}
