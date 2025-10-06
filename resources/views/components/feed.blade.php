@props(['posts'])

<div class="w-full">
    @forelse ($posts as $index => $post)
        <x-post :post="$post" :index="$index + 1" />
    @empty
        <div class="text-center text-gray-600">
            <p>No posts yet. Press 'p' to create one!</p>
        </div>
    @endforelse
</div>
