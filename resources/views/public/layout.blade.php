<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · '.config('app.name') : config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-900 antialiased">
    <header class="border-b border-stone-300 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-5">
            <a href="{{ route('home') }}" class="font-serif text-2xl font-bold tracking-tight">{{ config('app.name') }}</a>
            <nav class="flex items-center gap-5 text-sm font-semibold">
                @auth
                    <a href="{{ route('dashboard') }}" class="hover:text-orange-600">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="hover:text-orange-600">Profile</a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-orange-600">Log in</a>
                    <a href="{{ route('register') }}" class="border border-stone-900 px-4 py-2 hover:bg-stone-900 hover:text-white">Register</a>
                @endauth
            </nav>
        </div>
    </header>
    <main>{{ $slot }}</main>
    <footer class="mt-16 border-t border-stone-300 py-8 text-center text-sm text-stone-600">Published with {{ config('app.name') }}</footer>
</body>
</html>
