@props(['user', 'headerSelectedIndex' => 0])

<div class="w-full">
    {{-- Header Navigation --}}
    <div class="flex justify-between">
        <div class="flex space-x-3">
            <span class="text-cyan font-bold">◉ supo</span>
            @if($headerSelectedIndex === 0)
                <span class="bg-cyan text-black font-bold">▸ following</span>
            @else
                <span class="text-gray">following</span>
            @endif
            @if($headerSelectedIndex === 1)
                <span class="bg-cyan text-black font-bold">▸ explore</span>
            @else
                <span class="text-gray">explore</span>
            @endif
        </div>
        @if($headerSelectedIndex === 2)
            <span class="bg-cyan text-black font-bold">▸ {{ '@' . $user->username }}</span>
        @else
            <span class="text-cyan font-bold">{{ '@' . $user->username }}</span>
        @endif
    </div>
    
    {{-- Header Divider --}}
    <div class="w-full">
        <span class="text-cyan">━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━</span>
    </div>
</div>
