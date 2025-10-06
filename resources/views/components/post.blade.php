@props(['post'])

<div class="pb-2 border-b">
    <div class="flex">
        <div class="flex-1">
            <div class="flex space-x-1">
                <span class="text-white font-bold">{{ $post->user->username }}</span>
                <span class="text-gray">·</span>
                <span class="text-gray text-sm">{{ $post->created_at->diffForHumans() }}</span>
            </div>

            <div class="text-white">
                {{ $post->content }}
            </div>

            <div class="flex space-x-4 text-gray text-sm">
                <span>{{ $post->likes->count() }} likes</span>
            </div>
        </div>
    </div>
</div>
