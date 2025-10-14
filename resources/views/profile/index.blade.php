@props(['user', 'posts', 'selectedIndex', 'totalPosts', 'currentPosition', 'headerSelectedIndex', 'scrollOffset'])

<div class="w-full">
    {{-- Header --}}
    <x-header :user="$user" :headerSelectedIndex="$headerSelectedIndex" />

    {{-- Profile Info --}}
    <x-profile-info :user="$user" />

    {{-- Feed --}}
    @forelse ($posts as $index => $post)
        <x-post 
            :post="$post" 
            :user="$user" 
            :isSelected="$index === $selectedIndex" 
        />
    @empty
        <x-empty-state />
    @endforelse

    {{-- Footer --}}
    <x-footer 
        :currentPosition="$currentPosition" 
        :totalPosts="$totalPosts" 
    />
</div>
