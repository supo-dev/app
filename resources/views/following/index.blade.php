@props(['user', 'posts', 'selectedIndex', 'totalPosts', 'currentPosition', 'headerSelectedIndex' => 0])

<div class="w-full">
    {{-- Header --}}
    <x-header :user="$user" :headerSelectedIndex="$headerSelectedIndex" />

    {{-- Feed --}}
    @forelse ($posts as $index => $post)
        <x-post 
            :post="$post" 
            :user="$user" 
            :isSelected="isset($selectedIndex) && $index === $selectedIndex" 
        />
    @empty
        <x-empty-state 
            title="No posts in your feed" 
            message="Follow some users to see their posts" 
        />
    @endforelse

    {{-- Footer --}}
    @if(isset($currentPosition))
        <x-footer 
            :currentPosition="$currentPosition" 
            :totalPosts="$totalPosts" 
        />
    @else
        <x-footer instructions="↑↓ navigate • l like • f follow • q quit" />
    @endif
</div>
