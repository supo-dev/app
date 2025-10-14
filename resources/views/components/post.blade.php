@props(['post', 'user', 'isSelected' => false, 'showDivider' => true])

@php
    $bgClass = $isSelected ? 'bg-cyan' : '';
    $textClass = $isSelected ? 'text-black' : 'text-white';
    $grayClass = $isSelected ? 'text-gray-800' : 'text-gray';
    $likedByCurrentUser = $post->likes->contains('user_id', $user->id);
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

@if($showDivider)
    <div class="w-full">
        <span class="text-gray">────────────────────────────────────────────────────────────────────</span>
    </div>
@endif
