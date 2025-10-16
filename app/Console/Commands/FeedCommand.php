<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\LikeAction;
use App\Actions\RepostAction;
use App\Actions\UnlikeAction;
use App\Actions\UnrepostAction;
use App\Models\Post;
use App\Models\User;
use App\Queries\FollowingFeedQuery;
use App\Queries\TrendingFeedQuery;
use App\Queries\UserPostsQuery;
use Illuminate\Console\Command;
use Illuminate\Container\Attributes\CurrentUser;
use Laravel\Prompts\Key;

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

    private string $selectedAction = 'like';

    public function handle(
        #[CurrentUser] User $user,
        TrendingFeedQuery $trendingFeedQuery,
        FollowingFeedQuery $followingFeedQuery,
        UserPostsQuery $userPostsQuery,
        LikeAction $likeAction,
        UnlikeAction $unlikeAction,
        RepostAction $repostAction,
        UnrepostAction $unrepostAction
    ): void {
        $terminalHeight = (int) exec('tput lines');
        $headerLines = 3;
        $footerLines = 1;
        $profileInfoLines = $this->activeFeed === 'profile' ? 4 : 0;
        $linesPerPost = 6;
        $postsPerPage = max(1, (int) floor(($terminalHeight - $headerLines - $footerLines - $profileInfoLines) / $linesPerPost));

        $feeds = [
            'following' => function () use ($followingFeedQuery, $user): array {
                $posts = $followingFeedQuery->builder()->limit(100)->get();
                $followingIds = collect($user->following()->pluck('users.id'))->push($user->id);

                return $posts->map(function (Post $post) use ($followingIds): array {
                    $repost = $post->reposts->whereIn('user_id', $followingIds)->first();

                    return [
                        'post' => $post,
                        'reposted_by' => $repost?->user,
                    ];
                })->toArray();
            },
            'trending' => fn () => $trendingFeedQuery->builder()->limit(100)->get()->map(fn (Post $post): array => ['post' => $post, 'reposted_by' => null])->values()->toArray(),
            'profile' => fn () => $userPostsQuery->builder()->limit(100)->get()->map(fn (Post $post): array => ['post' => $post, 'reposted_by' => null])->values()->toArray(),
        ];

        /** @var array<int, array{post: Post, reposted_by: User|null}> $enrichedPosts */
        $enrichedPosts = $feeds[$this->activeFeed]();

        if (env('SUPO_NON_INTERACTIVE', false)) { // @phpstan-ignore-line
            $this->renderFeed($user, $enrichedPosts, $postsPerPage);

            return;
        }

        while (true) {
            system('clear');

            $this->renderFeed($user, $enrichedPosts, $postsPerPage);

            $key = $this->captureKeyPress();

            if ($key === Key::UP || $key === 'k') {
                if ($this->selectedIndex >= 0) {
                    $this->selectedIndex = max(-3, $this->selectedIndex - 1);
                    $this->selectedAction = 'like'; // Reset to like when changing selection
                }
            } elseif ($key === Key::DOWN || $key === 'j') {
                $this->selectedIndex = $this->selectedIndex < 0 ? 0 : min(count($enrichedPosts) - 1, $this->selectedIndex + 1);
                if ($this->selectedIndex >= 0) {
                    $this->selectedAction = 'like'; // Reset to like when changing selection
                }
            } elseif (($key === Key::LEFT || $key === 'h') && $this->selectedIndex < 0) {
                $this->selectedIndex = min(-1, $this->selectedIndex + 1);
            } elseif (($key === Key::LEFT || $key === 'h') && $this->selectedIndex >= 0) {
                $this->selectedAction = 'like';
            } elseif ($key === Key::RIGHT && $this->selectedIndex < 0) {
                $this->selectedIndex = max(-3, $this->selectedIndex - 1);
            } elseif (($key === Key::RIGHT || $key === 'l') && $this->selectedIndex >= 0) {
                $this->selectedAction = 'repost';
            } elseif ((in_array($key, [Key::ENTER, "\r", 'l'], true)) && $this->selectedIndex >= 0) {
                $post = $enrichedPosts[$this->selectedIndex]['post'];

                if ($this->selectedAction === 'like') {
                    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);
                    if ($likedByCurrentUser) {
                        $unlikeAction->handle($post, $user);
                    } else {
                        $likeAction->handle($post, $user);
                    }
                } elseif ($this->selectedAction === 'repost') {
                    $repostedByCurrentUser = $post->reposts->contains('user_id', $user->id);
                    if ($repostedByCurrentUser) {
                        $unrepostAction->handle($post, $user);
                    } else {
                        $repostAction->handle($post, $user);
                    }
                }

                /** @var array<int, array{post: Post, reposted_by: User|null}> $enrichedPosts */
                $enrichedPosts = $feeds[$this->activeFeed]();
            } elseif ($key === Key::ENTER || $key === "\r") {
                if ($this->selectedIndex < 0) {
                    $headerIndex = abs($this->selectedIndex) - 1;
                    $feedKeys = ['following', 'trending', 'profile'];
                    $this->activeFeed = $feedKeys[$headerIndex];
                    /** @var array<int, array{post: Post, reposted_by: User|null}> $enrichedPosts */
                    $enrichedPosts = $feeds[$this->activeFeed]();
                    $this->scrollOffset = 0;
                } else {
                    $post = $enrichedPosts[$this->selectedIndex]['post'];
                    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);

                    if ($likedByCurrentUser) {
                        $unlikeAction->handle($post, $user);
                    } else {
                        $likeAction->handle($post, $user);
                    }

                    /** @var array<int, array{post: Post, reposted_by: User|null}> $enrichedPosts */
                    $enrichedPosts = $feeds[$this->activeFeed]();
                }
            } elseif ($key === 'q') {
                outro('See you later!');

                return;
            } elseif ($key === 'e' && $this->activeFeed === 'profile') {
                system('clear');
                $this->call(EditProfileCommand::class);
                /** @var array<int, array{post: Post, reposted_by: User|null}> $enrichedPosts */
                $enrichedPosts = $feeds[$this->activeFeed]();
            }
        }
    }

    /**
     * @param  array<int, array{post: Post, reposted_by: User|null}>  $enrichedPosts
     */
    private function renderFeed(User $user, array $enrichedPosts, int $postsPerPage): void
    {
        $isHeaderSelected = $this->selectedIndex < 0;
        $postSelectedIndex = $this->selectedIndex >= 0 ? $this->selectedIndex : null;

        if ($postSelectedIndex !== null && $postSelectedIndex >= $this->scrollOffset + $postsPerPage) {
            $this->scrollOffset = min($postSelectedIndex - $postsPerPage + 1, max(0, count($enrichedPosts) - $postsPerPage));
        } elseif ($postSelectedIndex !== null && $postSelectedIndex < $this->scrollOffset) {
            $this->scrollOffset = max(0, $postSelectedIndex);
        }

        $this->scrollOffset = max(0, min($this->scrollOffset, max(0, count($enrichedPosts) - $postsPerPage)));

        $visiblePosts = array_slice($enrichedPosts, $this->scrollOffset, $postsPerPage);

        $viewName = $this->activeFeed === 'profile' ? 'profile.index' : 'feed.index';

        $feedKeys = ['following', 'trending', 'profile'];
        $activeFeedIndex = array_search($this->activeFeed, $feedKeys);

        render(view($viewName, [
            'user' => $user,
            'posts' => $visiblePosts,
            'selectedIndex' => $postSelectedIndex !== null ? $postSelectedIndex - $this->scrollOffset : null,
            'totalPosts' => count($enrichedPosts),
            'currentPosition' => $postSelectedIndex !== null ? $postSelectedIndex + 1 : null,
            'headerSelectedIndex' => $isHeaderSelected ? abs($this->selectedIndex) - 1 : $activeFeedIndex,
            'selectedAction' => $this->selectedAction,
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
