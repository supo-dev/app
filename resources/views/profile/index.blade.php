@props(['user', 'posts', 'selectedIndex', 'totalPosts', 'currentPosition', 'headerSelectedIndex', 'scrollOffset'])

<div class="w-full">
    {{-- Header --}}
    <div class="flex justify-between">
        <div class="flex space-x-3">
            <span class="text-cyan font-bold">◉ supo</span>
            @if($headerSelectedIndex === 0)
                <span class="bg-cyan text-black font-bold">▸ following</span>
            @else
                <span class="text-gray">following</span>
            @endif
            @if($headerSelectedIndex === 1)
                <span class="bg-cyan text-black font-bold">▸ trending</span>
            @else
                <span class="text-gray">trending</span>
            @endif
        </div>
        @if($headerSelectedIndex === 2)
            <span class="bg-cyan text-black font-bold">▸ {{ '@' . $user->username }}</span>
        @else
            <span class="text-cyan font-bold">{{ '@' . $user->username }}</span>
        @endif
    </div>
    <div class="w-full">
        <span class="text-cyan">━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>
    </div>

    {{-- Profile Info --}}
    <div class="w-full mt-1">
        <div class="flex space-x-2">
            <span class="text-white font-bold">{{ $user->name }}</span>
            <span class="text-gray">{{ '@' . $user->username }}</span>
        </div>
        @if($user->bio)
            <div class="text-white">{{ $user->bio }}</div>
        @endif
        <div class="flex space-x-4 text-gray mt-1">
            <span><span class="text-white font-bold">{{ $user->following()->count() }}</span> following</span>
            <span><span class="text-white font-bold">{{ $user->followers()->count() }}</span> followers</span>
            <span><span class="text-white font-bold">{{ $user->posts()->count() }}</span> posts</span>
        </div>
    </div>
    <div class="w-full">
        <span class="text-gray">────────────────────────────────────────────────────────────────────</span>
    </div>

    {{-- Feed --}}
    @forelse ($posts as $index => $post)
        @php
            $isSelected = $index === $selectedIndex;
            $bgClass = $isSelected ? 'bg-cyan' : '';
            $textClass = $isSelected ? 'text-black' : 'text-white';
            $grayClass = $isSelected ? 'text-gray-800' : 'text-gray';
        @endphp

        <div class="w-full {{ $bgClass }}">
            <div class="flex space-x-2">
                @if($isSelected)
                    <span class="{{ $textClass }} font-bold">▸ {{ $post->user->username }}</span>
                @else
                    <span class="{{ $textClass }} font-bold">{{ $post->user->username }}</span>
                @endif
                <span class="{{ $grayClass }}">{{ '@' . $post->user->username }}</span>
                <span class="{{ $grayClass }}">·</span>
                <span class="{{ $grayClass }}">{{ $post->created_at->diffForHumans() }}</span>
            </div>
            <div class="{{ $textClass }}">{{ $post->content }}</div>
            <div class="flex space-x-4 {{ $grayClass }}">
                @php
                    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);
                @endphp
                @if($likedByCurrentUser)
                    <span class="text-red font-bold">♥ {{ $post->likes->count() }}</span>
                @elseif($post->likes->count() > 0)
                    <span class="text-red font-bold">♡ {{ $post->likes->count() }}</span>
                @else
                    <span>♡ like</span>
                @endif
                <span>↻ repost</span>
            </div>
        </div>
        <div class="w-full">
            <span class="text-gray">────────────────────────────────────────────────────────────────────</span>
        </div>
    @empty
        <div class="flex justify-center mt-4">
            <div class="text-center">
                <div class="text-cyan">╭─────────────────────────────╮</div>
                <div class="text-gray">│                             │</div>
                <div><span class="text-gray">│   </span><span class="text-cyan font-bold">No posts yet</span><span class="text-gray">              │</span></div>
                <div class="text-gray">│                             │</div>
                <div><span class="text-gray">│  </span><span class="text-white">Start sharing your thoughts</span><span class="text-gray">  │</span></div>
                <div class="text-gray">│                             │</div>
                <div class="text-cyan">╰─────────────────────────────╯</div>
            </div>
        </div>
    @endforelse

    {{-- Footer --}}
    <div class="flex justify-between text-gray">
        @if($currentPosition !== null)
            <span>{{ $currentPosition }}/{{ $totalPosts }} • ↑↓ navigate • l like • enter select • q quit</span>
        @else
            <span>header navigation • ↑↓←→ navigate • enter select • q quit</span>
        @endif
        <span>powered by supo ◉</span>
    </div>
</div>
