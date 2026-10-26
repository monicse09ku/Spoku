<x-app-layout>
    <div>
        <section class="spoku-hero">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-28">
                <div class="spoku-hero-copy animate-rise-in">
                    <p class="spoku-kicker">Community accountability</p>
                    <h1 class="mt-4 font-display text-5xl sm:text-6xl leading-tight">
                        SPO<span class="text-signal">KU</span>
                    </h1>
                    <p class="mt-5 max-w-xl text-lg text-white/80">
                        Verified public reports of predatory behavior — screened by admins, confirmed by approvers, then published.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        @auth
                            @if(auth()->user()->role === \App\Enums\UserRole::User)
                                <a href="{{ route('user.complaints.create') }}" class="spoku-btn-primary">File a complaint</a>
                            @else
                                <a href="{{ route('dashboard') }}" class="spoku-btn-primary">Open dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="spoku-btn-primary">Register to report</a>
                            <a href="{{ route('login') }}" class="spoku-btn-secondary !bg-white/10 !text-white !border-white/30 hover:!bg-white/20">Log in</a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8 animate-rise-in" style="animation-delay: 120ms">
                <div>
                    <h2 class="spoku-section-title">Published reports</h2>
                    <p class="mt-2 spoku-muted">Visible only after admin screening, approver confirmation, and final admin publish.</p>
                </div>
            </div>

            <div class="space-y-0 border-t border-paper-line">
                @forelse($complaints as $complaint)
                    <a href="{{ route('feed.show', $complaint) }}" class="group grid grid-cols-[88px_1fr] sm:grid-cols-[112px_1fr_auto] gap-4 sm:gap-6 py-6 border-b border-paper-line hover:bg-white/50 transition animate-rise-in" style="animation-delay: {{ 140 + $loop->index * 40 }}ms">
                        @if($complaint->person->image_path)
                            <img src="{{ asset('storage/'.$complaint->person->image_path) }}" alt="" class="w-[88px] h-[88px] sm:w-[112px] sm:h-[112px] object-cover">
                        @else
                            <div class="w-[88px] h-[88px] sm:w-[112px] sm:h-[112px] bg-ink/5 flex items-center justify-center text-xs text-ink-mute">No photo</div>
                        @endif

                        <div class="min-w-0">
                            <h3 class="font-display text-2xl text-ink group-hover:text-signal transition">{{ $complaint->person->name }}</h3>
                            <p class="mt-2 text-ink-mute line-clamp-2">{{ $complaint->title }}</p>
                            <p class="mt-3 text-xs uppercase tracking-wider text-ink-mute">{{ $complaint->reactions_count }} reactions</p>
                        </div>

                        <div class="hidden sm:flex items-center text-sm font-semibold text-signal">
                            View report →
                        </div>
                    </a>
                @empty
                    <div class="py-16 text-center">
                        <p class="font-display text-2xl text-ink">No published reports yet</p>
                        <p class="mt-2 spoku-muted">Approved complaints will appear here.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $complaints->links() }}</div>
        </section>
    </div>
</x-app-layout>
