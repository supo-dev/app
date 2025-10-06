@props(['posts'])

<div>
    @forelse ($posts as $post)
        <x-post :post="$post" />
    @empty
        <div class="text-center text-gray">
            <p>No posts yet</p>
        </div>
    @endforelse
</div>
