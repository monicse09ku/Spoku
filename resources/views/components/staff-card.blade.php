@props([
    'user',
    'showRole' => true,
    'showContact' => false,
])

<div class="flex gap-4 items-start border-b border-paper-line pb-5">
    @if($user->imageUrl())
        <img src="{{ $user->imageUrl() }}" alt="{{ $user->name }}" class="w-20 h-20 object-cover shrink-0">
    @else
        <div class="w-20 h-20 bg-ink/5 flex items-center justify-center text-xs text-ink-mute shrink-0">
            No photo
        </div>
    @endif

    <div class="min-w-0">
        <div class="font-semibold text-ink text-lg leading-tight">{{ $user->name }}</div>
        @if($showRole)
            <div class="mt-1 text-xs uppercase tracking-[0.16em] text-signal">{{ $user->role->label() }}</div>
        @endif
        @if($showContact)
            <div class="mt-2 text-sm text-ink-mute">{{ $user->email }}</div>
            @if($user->phone)
                <div class="text-sm text-ink-mute">{{ $user->phone }}</div>
            @endif
            @if($user->contact_info)
                <div class="text-sm text-ink-soft mt-2">{{ $user->contact_info }}</div>
            @endif
        @endif
    </div>
</div>
