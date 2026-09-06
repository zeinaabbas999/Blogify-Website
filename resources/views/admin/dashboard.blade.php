@extends('layouts.admin')

@section('content')

    <div class="space-y-8">

        {{-- Page Heading --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Dashboard
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Welcome back, {{ auth()->user()->name }}.
                Here's an overview of your Blogify.
            </p>
        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">


            {{-- Total Posts --}}
            <a href="{{ route('admin.posts.index') }}"
               title="View all posts"
               class="block rounded-xl border border-slate-800 bg-slate-900 p-6 transition hover:border-indigo-500 hover:bg-slate-800 hover:underline">

                <p class="text-sm font-medium text-slate-400">
                    Total Posts
                </p>

                <p class="mt-3 text-3xl font-bold text-white">
                    {{ $postsCount }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    All posts
                </p>

            </a>


            {{-- Published Posts --}}
            <a href="{{ route('admin.posts.index') }}"
               title="View published posts"
               class="block rounded-xl border border-slate-800 bg-slate-900 p-6 transition hover:border-indigo-500 hover:bg-slate-800 hover:underline">

                <p class="text-sm font-medium text-slate-400">
                    Published Posts
                </p>

                <p class="mt-3 text-3xl font-bold text-white">
                    {{ $publishedPosts }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Currently published
                </p>

            </a>


            {{-- Draft Posts --}}
            <a href="{{ route('admin.posts.index') }}"
               title="View draft posts"
               class="block rounded-xl border border-slate-800 bg-slate-900 p-6 transition hover:border-indigo-500 hover:bg-slate-800 hover:underline">

                <p class="text-sm font-medium text-slate-400">
                    Draft Posts
                </p>

                <p class="mt-3 text-3xl font-bold text-white">
                    {{ $draftPosts }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Unpublished posts
                </p>

            </a>


            {{-- Total Users --}}
            <a href="#"
               title="View registered users"
               class="block rounded-xl border border-slate-800 bg-slate-900 p-6 transition hover:border-indigo-500 hover:bg-slate-800 hover:underline">

                <p class="text-sm font-medium text-slate-400">
                    Total Users
                </p>

                <p class="mt-3 text-3xl font-bold text-white">
                    {{ $usersCount }}
                </p>

                <p class="mt-2 text-xs text-slate-500">
                    Registered users
                </p>

            </a>

        </div>


        {{-- Quick Actions --}}
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

            <h2 class="text-lg font-semibold text-white">
                Quick Actions
            </h2>



            <div class="mt-5 flex gap-3">

                {{-- Create Post --}}
                <a href="{{ route('admin.posts.create') }}"
                   title="Create a new post"
                   class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-500 hover:underline">

                    + Create Post

                </a>


                {{-- Categories --}}
                <a href="{{ route('admin.categories.index') }}"
                   title="Manage post categories"
                   class="rounded-lg border border-slate-700 px-4 py-2.5 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:underline">

                    Categories

                </a>


                {{-- Tags --}}
                <a href="{{ route('admin.tags.index') }}"
                   title="Manage post tags"
                   class="rounded-lg border border-slate-700 px-4 py-2.5 text-sm font-medium text-slate-300 hover:bg-slate-800 hover:underline">

                    Tags

                </a>

            </div>

        </div>

    </div>

@endsection