@props(['user', 'posts', 'selectedIndex', 'totalPosts', 'currentPosition', 'headerSelectedIndex', 'selectedAction' => 'like', 'scrollOffset'])

<div class="w-full">
    {{-- Header --}}
    <x-header :user="$user" :headerSelectedIndex="$headerSelectedIndex" />

    {{-- Profile Info --}}
    <x-profile-info :user="$user" />

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
        <x-empty-state />
    @endforelse

    {{-- Footer --}}
    <x-footer 
        :currentPosition="$currentPosition" 
        :totalPosts="$totalPosts" 
    />
</div>
