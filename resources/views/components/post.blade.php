@props(['post', 'index' => null])

<div class="flex">
    @if($index !== null)
        <div class="w-4 text-gray-600 text-right">
            <span>{{ $index }}.</span>
        </div>
    @endif

    <div class="flex-1 pl-2">
        <div class="flex space-x-2">
            <span class="text-cyan">{{ '@' . $post->user->username }}</span>
            <span class="text-gray-600">{{ $post->created_at->diffForHumans() }}</span>
        </div>

        <div class="text-white">
            {{ $post->content }}
        </div>

        <div class="flex space-x-3 text-gray-600">
            <span>♥ {{ $post->likes->count() }}</span>
        </div>
    </div>
</div>
