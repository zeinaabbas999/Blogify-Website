<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Admin Panel' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="min-h-screen">

        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 border-r border-slate-800 bg-slate-950">

            <div class="flex h-full flex-col">

                {{-- Logo --}}
                <div class="flex h-16 items-center border-b border-slate-800 px-6">
                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-25 items-center justify-center rounded-lg bg-indigo-600 font-bold">
                            Blogify
                        </div>

                    </div>
                </div>


                {{-- Navigation --}}
                <nav class="flex-1 space-y-6 overflow-y-auto p-4">

                    {{-- Main --}}
                    <div>

                        <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Main
                        </p>

                        <a href="{{ route('admin.dashboard') }}"
                           title="Go to the admin dashboard"
                           class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-200 transition hover:bg-slate-800 hover:text-white hover:underline">

                            <span>▣</span>

                            Dashboard

                        </a>

                    </div>


                    {{-- Content --}}
                    <div>

                        <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Content
                        </p>

                        <div class="space-y-1">

                            {{-- Posts --}}
                            <a href="{{ route('admin.posts.index') }}"
                               title="View and manage all posts"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white hover:underline">

                                <span>▤</span>

                                Posts

                            </a>


                            {{-- Categories --}}
                            <a href="{{ route('admin.categories.index') }}"
                               title="Manage post categories"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white hover:underline">

                                <span>▦</span>

                                Categories

                            </a>


                            {{-- Tags --}}
                            <a href="{{ route('admin.tags.index') }}"
                               title="Manage post tags"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white hover:underline">

                                <span>#</span>

                                Tags

                            </a>

                        </div>

                    </div>


                    {{-- System --}}
                    <div>

                        <p class="mb-2 px-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            System
                        </p>

                        <div class="space-y-1">

                            {{-- Users --}}
                            <a href="{{ route('admin.users.index') }}"
                               title="Manage registered users"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white hover:underline">

                                <span>♙</span>

                                Users

                            </a>


                            {{-- Settings --}}
                            <a href="{{ route('admin.settings.index') }}"
                               title="Manage system settings"
                               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-300 transition hover:bg-slate-800 hover:text-white hover:underline">

                                <span>⚙</span>

                                Settings

                            </a>

                        </div>

                    </div>

                </nav>

            </div>

        </aside>


        {{-- Main area --}}
        <div class="ml-64 min-h-screen pt-16">

            {{-- Top navigation --}}
            <header
                class="fixed top-0 right-0 z-30 h-16 border-b border-slate-800 bg-slate-950/95 backdrop-blur"
                style="left: 16rem;">

                <div class="flex h-full items-center justify-between px-6">

                    {{-- Left side --}}
                    <div>

                        <h2 class="text-sm font-medium text-slate-400">
                            Administration
                        </h2>

                    </div>


                    {{-- Right side --}}
                    <div class="flex items-center gap-4">

                        {{-- User --}}
                        <div class="flex items-center gap-3 border-l border-slate-800 pl-4">

                            <div class="hidden text-right sm:block">

                                <p class="text-sm font-medium text-white">
                                    {{ auth()->user()->name }}
                                </p>

                                <p class="text-xs text-slate-500">
                                    {{ auth()->user()->email }}
                                </p>

                            </div>


                            {{-- Avatar --}}
                            <div
                                title="Logged in as {{ auth()->user()->name }}"
                                class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-600 text-sm font-semibold text-white">

                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                            </div>

                        </div>

                    </div>

                </div>

            </header>


            {{-- Page content --}}
            <main class="p-8">

                @yield('content')

            </main>

        </div>

    </div>

</body>
</html>