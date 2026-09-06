@extends('layouts.admin')

@section('content')

    <div class="space-y-6">

        {{-- Page header --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Posts
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Manage your blog posts.
                </p>
            </div>

            <a href="{{ route('admin.posts.create') }}"
               title="Create a new post"
               class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white
                      transition hover:bg-indigo-500 hover:underline">
                + Create Post
            </a>

        </div>


        {{-- Posts --}}
        <div class="space-y-4">

            @forelse ($posts as $post)

                <div class="rounded-xl border border-slate-800 bg-slate-900 p-5
                            transition hover:border-slate-700">

                    <div class="flex items-start justify-between gap-6">

                        {{-- Post information --}}
                        <div class="min-w-0 flex-1">

                            <a href="{{ route('admin.posts.show', $post) }}"
                               class="text-lg font-semibold text-white hover:underline">
                                {{ $post->title }}
                            </a>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ $post->excerpt ?: 'No excerpt provided.' }}
                            </p>


                            {{-- Metadata --}}
                            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs">

                                {{-- Status --}}
                                @if ($post->status->value === 'published')

                                    <span class="rounded-full bg-green-900/30 px-3 py-1 text-green-400">
                                        Published
                                    </span>

                                @else

                                    <span class="rounded-full bg-yellow-900/30 px-3 py-1 text-yellow-400">
                                        Draft
                                    </span>

                                @endif


                                {{-- Category --}}
                                @if ($post->category)

                                    <span class="rounded-full bg-slate-800 px-3 py-1 text-slate-300">
                                        {{ $post->category->name }}
                                    </span>

                                @endif


                                {{-- Author --}}
                                @if ($post->user)

                                    <span class="text-slate-500">
                                        By {{ $post->user->name }}
                                    </span>

                                @endif


                                {{-- Date --}}
                                <span class="text-slate-500">
                                    {{ $post->created_at->format('M d, Y') }}
                                </span>

                            </div>


                            {{-- Tags --}}
                            @if ($post->tags->count())

                                <div class="mt-3 flex flex-wrap gap-2">

                                    @foreach ($post->tags as $tag)

                                        <span class="rounded-md bg-slate-800 px-2.5 py-1 text-xs text-slate-400">
                                            #{{ $tag->name }}
                                        </span>

                                    @endforeach

                                </div>

                            @endif

                        </div>


                        {{-- Actions --}}
                        <div class="flex shrink-0 items-center gap-2">

                            <a href="{{ route('admin.posts.show', $post) }}"
                               class="rounded-lg border border-slate-500 px-3 py-2
                                      text-sm text-slate-200 transition
                                      hover:bg-slate-800 hover:underline">
                                View
                            </a>

                            @can('update', $post)
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="rounded-lg bg-indigo-600 px-3 py-2
                                      text-sm font-medium text-white transition
                                      hover:bg-indigo-500">
                                Edit
                            </a>
                            @endcan

                            @can('delete', $post)
                            <form action="{{ route('admin.posts.destroy', $post) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this post?');">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg border border-red-800 px-3 py-2
                                           text-sm text-red-400 transition
                                           hover:bg-red-900/30">
                                    Delete
                                </button>

                            </form>
                            @endcan

                        </div>

                    </div>

                </div>

            @empty

                <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

                    <p class="text-sm text-slate-400">
                        No posts found.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

@endsection
