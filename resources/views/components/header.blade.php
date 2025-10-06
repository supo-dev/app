@props(['user'])

<div class="flex justify-between pb-1">
    <div class="flex space-x-2">
        <span class="text-blue font-bold">Supo</span>
    </div>

    <div class="flex space-x-2">
        <span class="text-gray">{{ '@' . $user->username }}</span>
    </div>
</div>
