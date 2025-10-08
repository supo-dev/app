<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = \App\Models\User::factory(10)->create();

        $users->each(function (\App\Models\User $user) use ($users): void {
            \App\Models\Post::factory(rand(3, 8))
                ->for($user)
                ->create();

            \App\Models\SshKey::factory(rand(1, 3))
                ->for($user)
                ->create();

            $followersCount = rand(0, 5);
            if ($followersCount > 0) {
                $followers = $users->except($user->id)->random(min($followersCount, $users->count() - 1));
                $user->followers()->attach($followers->pluck('id'));
            }
        });

        $allPosts = \App\Models\Post::all();

        $users->each(function (\App\Models\User $user) use ($allPosts): void {
            $likesCount = rand(5, 15);
            $postsToLike = $allPosts->whereNotIn('user_id', [$user->id])
                ->random(min($likesCount, $allPosts->whereNotIn('user_id', [$user->id])->count()));

            foreach ($postsToLike as $post) {
                \App\Models\Like::factory()
                    ->for($user)
                    ->for($post)
                    ->create();
            }
        });
    }
}
