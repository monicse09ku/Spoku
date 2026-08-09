<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-ink-mute">Public report</p>
                <h2 class="font-display text-3xl text-ink mt-1">{{ $complaint->title }}</h2>
            </div>
            <a href="{{ route('home') }}" class="spoku-btn-ghost">← Back to feed</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            @if (session('status'))
                <div class="border border-trust/20 bg-trust-soft text-trust px-4 py-3 text-sm">{{ session('status') }}</div>
            @endif

            <section class="spoku-panel p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row gap-6">
                    @if($complaint->person->image_path)
                        <img src="{{ asset('storage/'.$complaint->person->image_path) }}" class="w-full sm:w-40 h-56 sm:h-40 object-cover" alt="">
                    @endif
                    <div>
                        <h3 class="font-display text-3xl">{{ $complaint->person->name }}</h3>
                        <p class="mt-2 text-sm text-ink-mute">Reported by {{ $complaint->submitterDisplayName() }}</p>
                        @if($complaint->person->description)
                            <p class="mt-4 text-ink-soft leading-relaxed">{{ $complaint->person->description }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-paper-line">
                    <h4 class="text-xs uppercase tracking-[0.18em] text-ink-mute">Complaint details</h4>
                    <p class="mt-3 whitespace-pre-line text-ink leading-relaxed">{{ $complaint->details }}</p>
                </div>

                @if($complaint->files->count())
                    <div class="mt-8 pt-6 border-t border-paper-line">
                        <h4 class="text-xs uppercase tracking-[0.18em] text-ink-mute mb-4">Evidence</h4>
                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach($complaint->files as $file)
                                <div class="border border-paper-line p-3">
                                    @if($file->type === 'image')
                                        <img src="{{ asset('storage/'.$file->path) }}" class="mb-2 max-h-48 w-full object-cover" alt="">
                                    @elseif($file->type === 'audio')
                                        <audio controls class="w-full" src="{{ asset('storage/'.$file->path) }}"></audio>
                                    @else
                                        <video controls class="w-full" src="{{ asset('storage/'.$file->path) }}"></video>
                                    @endif
                                    <div class="text-xs text-ink-mute truncate mt-2">{{ $file->original_name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </section>

            <section class="spoku-panel p-6 sm:p-8">
                <h4 class="font-display text-2xl text-ink">Community reactions</h4>
                <p class="mt-1 text-sm text-ink-mute">Login required to react.</p>

                <div class="mt-5 flex flex-wrap gap-2">
                    @foreach($reactionTypes as $type)
                        <span class="spoku-reaction pointer-events-none">
                            {{ $type->label() }}
                            <strong>{{ $reactionCounts[$type->value] ?? 0 }}</strong>
                        </span>
                    @endforeach
                </div>

                @auth
                    <form method="POST" action="{{ route('feed.react', $complaint) }}" class="mt-6 flex flex-wrap gap-2">
                        @csrf
                        @foreach($reactionTypes as $type)
                            <button name="type" value="{{ $type->value }}" class="{{ optional($userReaction)->type === $type ? 'spoku-reaction spoku-reaction-active' : 'spoku-reaction' }}">
                                {{ $type->label() }}
                            </button>
                        @endforeach
                    </form>
                @else
                    <p class="mt-6 text-sm text-ink-mute">
                        <a href="{{ route('login') }}" class="text-signal font-semibold underline underline-offset-2">Log in</a>
                        or
                        <a href="{{ route('register') }}" class="text-signal font-semibold underline underline-offset-2">register</a>
                        to leave a reaction.
                    </p>
                @endauth
            </section>
        </div>
    </div>
</x-app-layout>
