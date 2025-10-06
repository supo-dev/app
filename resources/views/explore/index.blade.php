@props(['user', 'posts'])

<div>
    <x-header :user="$user" active="explore" />

    <x-feed :posts="$posts" />
</div>
