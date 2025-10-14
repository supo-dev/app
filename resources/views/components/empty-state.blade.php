@props(['title' => 'No posts yet', 'message' => 'Start sharing your thoughts'])

<div class="flex justify-center mt-4">
    <div class="text-center">
        <div class="text-cyan">╭─────────────────────────────╮</div>
        <div class="text-gray">│                             │</div>
        <div><span class="text-gray">│   </span><span class="text-cyan font-bold">{{ $title }}</span><span class="text-gray">   │</span></div>
        <div class="text-gray">│                             │</div>
        <div><span class="text-gray">│  </span><span class="text-white">{{ $message }}</span><span class="text-gray">  │</span></div>
        <div class="text-gray">│                             │</div>
        <div class="text-cyan">╰─────────────────────────────╯</div>
    </div>
</div>