<nav x-data="{ open: false }" class="spoku-nav sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="spoku-brand">SPO<span>KU</span></a>

                <div class="hidden sm:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home', 'feed.*') ? 'spoku-link-active' : 'spoku-link' }}">
                        Public Feed
                    </a>
                    <a href="{{ route('team') }}" class="{{ request()->routeIs('team') ? 'spoku-link-active' : 'spoku-link' }}">
                        Team
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard', 'admin.*', 'approver.*', 'lawyer.*', 'counselor.*', 'law-enforcer.*', 'humanitarian.*', 'user.*') ? 'spoku-link-active' : 'spoku-link' }}">
                            Dashboard
                        </a>

                        @if(auth()->user()->role === \App\Enums\UserRole::User)
                            <a href="{{ route('user.complaints.create') }}" class="{{ request()->routeIs('user.complaints.create') ? 'spoku-link-active' : 'spoku-link' }}">
                                File Complaint
                            </a>
                            <a href="{{ route('user.directory') }}" class="{{ request()->routeIs('user.directory') ? 'spoku-link-active' : 'spoku-link' }}">
                                Directory
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                @guest
                    <a href="{{ route('login') }}" class="spoku-btn-ghost">Log in</a>
                    <a href="{{ route('register') }}" class="spoku-btn-primary">Register</a>
                @else
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="spoku-btn-secondary">
                                <span>{{ Auth::user()->name }}</span>
                                <span class="text-ink-mute font-normal">· {{ Auth::user()->role->label() }}</span>
                                <svg class="fill-current h-4 w-4 text-ink-mute" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @endguest
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="spoku-btn-ghost" aria-label="Toggle menu">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-paper-line bg-paper-raised">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('home') }}" class="block spoku-link">Public Feed</a>
            <a href="{{ route('team') }}" class="block spoku-link">Team</a>
            @auth
                <a href="{{ route('dashboard') }}" class="block spoku-link">Dashboard</a>
                @if(auth()->user()->role === \App\Enums\UserRole::User)
                    <a href="{{ route('user.complaints.create') }}" class="block spoku-link">File Complaint</a>
                    <a href="{{ route('user.directory') }}" class="block spoku-link">Directory</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block spoku-link">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="block spoku-link w-full text-left">Log Out</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block spoku-link">Log in</a>
                <a href="{{ route('register') }}" class="inline-flex spoku-btn-primary mt-2">Register</a>
            @endauth
        </div>
    </div>
</nav>
