@props(['user', 'posts'])

<div>
    <x-header :user="$user" />

    <div>
        <div>
            <span class="text-white font-bold">Following</span>
        </div>

        <x-feed :posts="$posts" />
    </div>
</div>
