@props(['currentPosition' => null, 'totalPosts' => 0, 'instructions' => null])

@php
    $defaultInstructions = $currentPosition !== null 
        ? "$currentPosition/$totalPosts • ↑↓ navigate • ←→ like/repost • enter action • e edit profile • q quit"
        : "header navigation • ↑↓←→ navigate • enter select • e edit profile • q quit";
    
    $displayInstructions = $instructions ?? $defaultInstructions;
@endphp
<div class="flex justify-between text-gray">
    <span>{{ $displayInstructions }}</span>
    <span>powered by supo ◉</span>
</div>