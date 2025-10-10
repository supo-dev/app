<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\LikeAction;
use App\Actions\UnlikeAction;
use App\Models\User;
use App\Queries\FollowingFeedQuery;
use App\Queries\TrendingFeedQuery;
use App\Queries\UserPostsQuery;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Database\Eloquent\Collection;

use function Laravel\Prompts\outro;
use function Termwind\render;

final class FeedCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:feed';

    private int $selectedIndex = -1;

    private int $scrollOffset = 0;

    private string $activeFeed = 'following';

    public function handle(
        #[CurrentUser] User $user,
        TrendingFeedQuery $trendingFeedQuery,
        FollowingFeedQuery $followingFeedQuery,
        UserPostsQuery $userPostsQuery,
        LikeAction $likeAction,
        UnlikeAction $unlikeAction
    ): void {
        $terminalHeight = (int) exec('tput lines');
        $headerLines = 3;
        $footerLines = 1;
        $profileInfoLines = $this->activeFeed === 'profile' ? 4 : 0;
        $linesPerPost = 6;
        $postsPerPage = max(1, (int) floor(($terminalHeight - $headerLines - $footerLines - $profileInfoLines) / $linesPerPost));

        $feeds = [
            'following' => fn () => $followingFeedQuery->builder()->limit(100)->get(),
            'trending' => fn () => $trendingFeedQuery->builder()->limit(100)->get(),
            'profile' => fn () => $userPostsQuery->builder()->limit(100)->get(),
        ];

        $posts = $feeds[$this->activeFeed]();

        if (env('SUPO_NON_INTERACTIVE', false)) { // @phpstan-ignore-line
            $this->renderFeed($user, $posts, $postsPerPage);

            return;
        }

        while (true) {
            system('clear');
            echo "\n";

            $this->renderFeed($user, $posts, $postsPerPage);

            $key = $this->captureKeyPress();

            if ($key === "\e[A" || $key === 'k') {
                if ($this->selectedIndex >= 0) {
                    $this->selectedIndex = max(-3, $this->selectedIndex - 1);
                }
            } elseif ($key === "\e[B" || $key === 'j') {
                $this->selectedIndex = $this->selectedIndex < 0 ? 0 : min($posts->count() - 1, $this->selectedIndex + 1);
            } elseif (($key === "\e[D" || $key === 'h') && $this->selectedIndex < 0) {
                $this->selectedIndex = min(-1, $this->selectedIndex + 1);
            } elseif ($key === "\e[C" && $this->selectedIndex < 0) {
                $this->selectedIndex = max(-3, $this->selectedIndex - 1);
            } elseif ($key === 'l' && $this->selectedIndex >= 0) {
                $post = $posts->all()[$this->selectedIndex];
                $likedByCurrentUser = $post->likes->contains('user_id', $user->id);

                if ($likedByCurrentUser) {
                    $unlikeAction->handle($post, $user);
                } else {
                    $likeAction->handle($post, $user);
                }

                $posts = $feeds[$this->activeFeed]();
            } elseif ($key === "\n" || $key === "\r") {
                if ($this->selectedIndex < 0) {
                    $headerIndex = abs($this->selectedIndex) - 1;
                    $feedKeys = ['following', 'trending', 'profile'];
                    $this->activeFeed = $feedKeys[$headerIndex];
                    $posts = $feeds[$this->activeFeed]();
                    $this->scrollOffset = 0;
                } else {
                    $post = $posts->all()[$this->selectedIndex];
                    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);

                    if ($likedByCurrentUser) {
                        $unlikeAction->handle($post, $user);
                    } else {
                        $likeAction->handle($post, $user);
                    }

                    $posts = $feeds[$this->activeFeed]();
                }
            } elseif ($key === 'q') {
                outro('See you later!');

                return;
            }
        }
    }

    /**
     * @param  Collection<int, \App\Models\Post>  $posts
     */
    private function renderFeed(User $user, Collection $posts, int $postsPerPage): void
    {
        $isHeaderSelected = $this->selectedIndex < 0;
        $postSelectedIndex = $this->selectedIndex >= 0 ? $this->selectedIndex : null;

        if ($postSelectedIndex !== null && $postSelectedIndex >= $this->scrollOffset + $postsPerPage) {
            $this->scrollOffset = min($postSelectedIndex - $postsPerPage + 1, max(0, $posts->count() - $postsPerPage));
        } elseif ($postSelectedIndex !== null && $postSelectedIndex < $this->scrollOffset) {
            $this->scrollOffset = max(0, $postSelectedIndex);
        }

        $this->scrollOffset = max(0, min($this->scrollOffset, max(0, $posts->count() - $postsPerPage)));

        $visiblePosts = $posts->slice($this->scrollOffset, $postsPerPage)->values();

        $viewName = $this->activeFeed === 'profile' ? 'profile.index' : 'feed.index';

        render(view($viewName, [
            'user' => $user,
            'posts' => $visiblePosts,
            'selectedIndex' => $postSelectedIndex !== null ? $postSelectedIndex - $this->scrollOffset : null,
            'totalPosts' => $posts->count(),
            'currentPosition' => $postSelectedIndex !== null ? $postSelectedIndex + 1 : null,
            'headerSelectedIndex' => $isHeaderSelected ? abs($this->selectedIndex) - 1 : null,
            'scrollOffset' => $this->scrollOffset,
        ])->render());
    }

    private function captureKeyPress(): string
    {
        system('stty cbreak -echo');
        $key = (string) fread(STDIN, 4);
        system('stty -cbreak echo');

        return $key;
    }
}
