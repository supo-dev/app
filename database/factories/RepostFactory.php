<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Post;
use App\Models\Repost;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Repost>
 */
final class RepostFactory extends Factory
{
    protected $model = Repost::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
        ];
    }
}
