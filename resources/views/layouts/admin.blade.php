<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CMS' }} · {{ config('app.name', 'Editorial') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-900 antialiased" x-data="{ menu: false }">
<header class="sticky top-0 z-40 border-b border-stone-300 bg-stone-950 text-white"><div class="mx-auto flex h-16 max-w-screen-2xl items-center justify-between px-4 sm:px-6">
    <div class="flex items-center gap-6"><button class="border border-stone-700 px-3 py-2 text-sm lg:hidden" @click="menu = !menu" aria-label="Toggle navigation">Menu</button><a href="{{ route('admin.dashboard') }}" class="font-serif text-xl font-bold">{{ config('app.name', 'Editorial') }}</a><span class="hidden border-l border-stone-700 pl-6 text-xs font-bold uppercase tracking-widest text-amber-400 sm:block">Publishing desk</span></div>
    <div class="flex items-center gap-4 text-sm"><a href="{{ route('home') }}" class="hidden text-stone-300 hover:text-white sm:block">View site</a><span class="hidden text-stone-400 md:block">{{ auth()->user()->getRoleNames()->first() }}</span><a href="{{ route('profile.edit') }}" class="font-semibold hover:text-amber-400">{{ auth()->user()->name }}</a><form action="{{ route('logout') }}" method="POST">@csrf<button class="text-stone-300 hover:text-white">Log out</button></form></div>
</div></header>
<div class="mx-auto grid min-h-[calc(100vh-4rem)] max-w-screen-2xl lg:grid-cols-[240px_1fr]">
    <aside :class="menu ? 'block' : 'hidden'" class="border-r border-stone-300 bg-white p-4 lg:block"><nav class="space-y-1 text-sm font-semibold">
        <a href="{{ route('admin.dashboard') }}" class="block border-l-4 px-3 py-2.5 {{ request()->routeIs('admin.dashboard') ? 'border-amber-600 bg-amber-50 text-amber-900' : 'border-transparent hover:bg-stone-100' }}">Dashboard</a>
        @can('view_posts')<a href="{{ route('admin.posts.index') }}" class="block border-l-4 px-3 py-2.5 {{ request()->routeIs('admin.posts.*') ? 'border-amber-600 bg-amber-50 text-amber-900' : 'border-transparent hover:bg-stone-100' }}">{{ auth()->user()->can('view_all_posts') ? 'Posts' : 'My posts' }}</a>@endcan
        @can('create_posts')<a href="{{ route('admin.posts.create') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Create post</a>@endcan
        @can('manage_categories')<a href="{{ route('admin.categories.index') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Categories</a>@endcan
        @can('manage_tags')<a href="{{ route('admin.tags.index') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Tags</a>@endcan
        @can('manage_users')<a href="{{ route('admin.users.index') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Users</a>@endcan
        @can('manage_settings')<a href="{{ route('admin.settings.index') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Settings</a>@endcan
        <a href="{{ route('profile.edit') }}" class="block border-l-4 border-transparent px-3 py-2.5 hover:bg-stone-100">Profile</a>
    </nav></aside>
    <main class="min-w-0 p-5 sm:p-8 lg:p-10">@if (session('success') || session('status'))<div class="mb-6 border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">{{ session('success') ?? session('status') }}</div>@endif @if (session('error'))<div class="mb-6 border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900">{{ session('error') }}</div>@endif @yield('content')</main>
</div>
</body></html>
