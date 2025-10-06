@props(['user', 'active' => 'following'])

<div class="w-full">
    <div class="flex justify-between">
        <div class="flex">
            <span class="text-cyan font-bold">supo</span>
        </div>
        <div>
            <span class="text-gray">{{ '@' . $user->username }}</span>
        </div>
    </div>

    <div class="flex space-x-4">
        @if($active === 'following')
            <span class="text-white font-bold">following</span>
        @else
            <span class="text-gray">following</span>
        @endif

        @if($active === 'explore')
            <span class="text-white font-bold">explore</span>
        @else
            <span class="text-gray">explore</span>
        @endif
    </div>
</div>
