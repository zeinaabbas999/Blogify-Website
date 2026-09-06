<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CMS' }} · {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 text-stone-900 antialiased" x-data="{ menu: false }">
<header class="sticky top-0 z-40 border-b border-stone-300 bg-stone-950 text-white"><div class="mx-auto flex h-16 max-w-screen-2xl items-center justify-between px-4 sm:px-6">
    <div class="flex items-center gap-6"><button class="border border-stone-700 px-3 py-2 text-sm hover:border-orange-600 lg:hidden" @click="menu = !menu" aria-label="Toggle navigation" :aria-expanded="menu">Menu</button><a href="{{ route('dashboard') }}" class="font-serif text-xl font-bold">{{ config('app.name') }}</a><span class="hidden border-l border-stone-700 pl-6 text-xs font-bold uppercase tracking-widest text-orange-600 sm:block">Publishing desk</span></div>
    <div class="flex items-center gap-3 text-sm">
        <a href="{{ route('profile.edit') }}" class="hidden text-right sm:block">
            <span class="block font-semibold text-white hover:text-orange-600">{{ auth()->user()->name }}</span>
            <span class="block text-xs text-stone-400">{{ auth()->user()->getRoleNames()->first() }}</span>
        </a>
        <span class="hidden h-7 border-l border-stone-700 sm:block" aria-hidden="true"></span>
        <a href="{{ route('home') }}" class="font-semibold text-stone-300 hover:text-white">View site</a>
        <form class="border-l border-stone-700 pl-3" action="{{ route('logout') }}" method="POST">@csrf<button class="text-stone-300 hover:text-white">Log out</button></form>
    </div>
</div></header>
<div class="cms-content mx-auto grid min-h-[calc(100vh-4rem)] max-w-screen-2xl lg:grid-cols-[240px_1fr]">
    <aside :class="menu ? 'block' : 'hidden'" @click.outside="menu = false" class="border-r border-stone-300 bg-white p-4 lg:block"><nav class="space-y-1 text-sm font-semibold">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('*.dashboard') ? 'nav-item-active' : '' }}">Dashboard</a>
        @can('view_posts')<a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts.*') ? 'nav-item-active' : '' }}">{{ auth()->user()->can('view_all_posts') ? 'Posts' : 'My posts' }}</a>@endcan
        @can('create_posts')<a href="{{ route('admin.posts.create') }}" class="nav-item">Create post</a>@endcan
        @can('manage_categories')<a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories.*') ? 'nav-item-active' : '' }}">Categories</a>@endcan
        @can('manage_tags')<a href="{{ route('admin.tags.index') }}" class="nav-item {{ request()->routeIs('admin.tags.*') ? 'nav-item-active' : '' }}">Tags</a>@endcan
        @can('manage_users')<a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'nav-item-active' : '' }}">Users</a>@endcan
        @can('manage_settings')<a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'nav-item-active' : '' }}">Settings</a>@endcan
        <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'nav-item-active' : '' }}">Profile</a>
    </nav></aside>
    <main class="min-w-0 p-5 sm:p-8 lg:p-10"><x-flash-messages />@yield('content')</main>
</div>
</body></html>
