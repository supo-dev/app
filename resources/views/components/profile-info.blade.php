@props(['user'])

<div class="w-full mt-1">
    <div class="flex space-x-2">
        <span class="text-white font-bold">{{ '@' . $user->username }}</span>
    </div>
    
    @if($user->bio)
        <div class="text-white">{{ $user->bio }}</div>
    @endif
    
    <div class="flex space-x-4 text-gray mt-1">
        <span><span class="text-white font-bold">{{ $user->following()->count() }}</span> following</span>
        <span><span class="text-white font-bold">{{ $user->followers()->count() }}</span> followers</span>
        <span><span class="text-white font-bold">{{ $user->posts()->count() }}</span> posts</span>
    </div>
</div>

<div class="w-full">
    <span class="text-gray">────────────────────────────────────────────────────────────────────</span>
</div>