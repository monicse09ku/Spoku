<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SPOKU'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=libre-baskerville:400,700|manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-ink">
        <div class="spoku-shell">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-paper-line/80 bg-paper-raised/70 backdrop-blur">
                    <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 animate-fade-in">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="animate-rise-in" style="animation-delay: 80ms">
                {{ $slot }}
            </main>

            <footer class="mt-16 border-t border-paper-line/80">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <p class="font-display text-lg text-ink">SPO<span class="text-signal">KU</span></p>
                    <p class="text-sm text-ink-mute">Accountability through verified community reports.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
