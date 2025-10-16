@props(['post', 'user', 'isSelected' => false, 'showDivider' => true, 'selectedAction' => 'like', 'repostedBy' => null])

@php
    $bgClass = $isSelected ? 'bg-cyan' : '';
    $textClass = $isSelected ? 'text-black' : 'text-white';
    $grayClass = $isSelected ? 'text-gray-800' : 'text-gray';
    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);
    $repostedByCurrentUser = $post->reposts->contains('user_id', $user->id);
@endphp

<div class="w-full {{ $bgClass }}">
    @if($repostedBy)
        <div class="flex space-x-1 {{ $grayClass }}">
            <span>↻</span>
            <span>{{ $repostedBy->username }} reposted</span>
        </div>
    @endif
    
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
        @if($isSelected && $selectedAction === 'like')
            @if($likedByCurrentUser)
                <span class="bg-red text-white font-bold">▸ ♥ {{ $post->likes->count() }}</span>
            @elseif($post->likes->count() > 0)
                <span class="bg-red text-white font-bold">▸ ♡ {{ $post->likes->count() }}</span>
            @else
                <span class="bg-red text-white font-bold">▸ ♡ like</span>
            @endif
        @else
            @if($likedByCurrentUser)
                <span class="text-red font-bold">♥ {{ $post->likes->count() }}</span>
            @elseif($post->likes->count() > 0)
                <span class="text-red font-bold">♡ {{ $post->likes->count() }}</span>
            @else
                <span>♡ like</span>
            @endif
        @endif

        @if($isSelected && $selectedAction === 'repost')
            @if($repostedByCurrentUser)
                <span class="bg-green text-white font-bold">▸ ↻ {{ $post->reposts->count() }}</span>
            @elseif($post->reposts->count() > 0)
                <span class="bg-green text-white font-bold">▸ ↻ {{ $post->reposts->count() }}</span>
            @else
                <span class="bg-green text-white font-bold">▸ ↻ repost</span>
            @endif
        @else
            @if($repostedByCurrentUser)
                <span class="text-green font-bold">↻ {{ $post->reposts->count() }}</span>
            @elseif($post->reposts->count() > 0)
                <span class="text-green font-bold">↻ {{ $post->reposts->count() }}</span>
            @else
                <span>↻ repost</span>
            @endif
        @endif
    </div>
</div>

@if($showDivider)
    <div class="w-full">
        <span class="text-gray">────────────────────────────────────────────────────────────────────</span>
    </div>
@endif
