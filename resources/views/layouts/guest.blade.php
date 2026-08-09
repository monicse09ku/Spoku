<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPOKU') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=libre-baskerville:400,700|manrope:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="spoku-shell">
            <nav class="spoku-nav">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <a href="{{ route('home') }}" class="spoku-brand">SPO<span>KU</span></a>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="spoku-btn-ghost">Log in</a>
                            <a href="{{ route('register') }}" class="spoku-btn-primary">Register</a>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="min-h-[calc(100vh-4rem)] flex flex-col justify-center items-center px-4 py-10">
                <div class="w-full max-w-md spoku-panel p-7 animate-rise-in">
                    <div class="mb-6 text-center">
                        <p class="spoku-brand text-3xl">SPO<span>KU</span></p>
                        <p class="mt-2 text-sm text-ink-mute">Secure access to the accountability platform</p>
                    </div>
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
