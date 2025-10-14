@props(['posts', 'user', 'selectedIndex' => null])

<div class="w-full">
    @forelse ($posts as $index => $post)
        <x-post 
            :post="$post" 
            :user="$user" 
            :isSelected="$selectedIndex !== null && $index === $selectedIndex" 
        />
    @empty
        <x-empty-state 
            title="No posts yet" 
            message="Press 'p' to create one!" 
        />
    @endforelse
</div>
