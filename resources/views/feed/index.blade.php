@props(['user', 'posts', 'selectedIndex', 'totalPosts', 'currentPosition', 'headerSelectedIndex', 'selectedAction' => 'like', 'scrollOffset'])

<div class="w-full">
    {{-- Header --}}
    <x-header :user="$user" :headerSelectedIndex="$headerSelectedIndex" />

    {{-- Feed --}}
    @forelse ($posts as $index => $enrichedPost)
        <x-post 
            :post="$enrichedPost['post']" 
            :user="$user" 
            :isSelected="$index === $selectedIndex" 
            :selectedAction="$selectedAction"
            :repostedBy="$enrichedPost['reposted_by']"
        />
    @empty
        @php
            $emptyTitle = $headerSelectedIndex === 1 ? 'Nothing trending right now' : 'No posts yet';
            $emptyMessage = $headerSelectedIndex === 1 ? 'Check back soon for updates' : 'Start sharing your thoughts';
        @endphp
        <x-empty-state :title="$emptyTitle" :message="$emptyMessage" />
    @endforelse

    {{-- Footer --}}
    @php
        $instructions = $currentPosition !== null 
            ? "$currentPosition/$totalPosts • ↑↓ navigate • l like • enter select • q quit"
            : "header navigation • ↑↓←→ navigate • enter select • q quit";
    @endphp
    <x-footer :instructions="$instructions" />
</div>
