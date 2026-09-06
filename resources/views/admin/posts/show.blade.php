@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                View Post
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                View the complete blog post.
            </p>
        </div>

        <div class="flex gap-3">

            <a href="{{ route('admin.posts.edit', $post) }}"
               class="rounded-lg bg-orange-600 px-4 py-2.5
                      text-sm font-medium text-white
                      transition hover:bg-orange-600 hover:underline">
                Edit Post
            </a>

            <a href="{{ route('admin.posts.index') }}"
               class="rounded-lg border border-slate-900
                      px-4 py-2.5 text-sm font-medium text-slate-700
                      transition hover:bg-slate-800 hover:underline">
                Back
            </a>

        </div>

    </div>


    {{-- Post --}}
    <div class="rounded-xl border border-slate-800 bg-slate-900 p-8">

        {{-- Title --}}
        <h2 class="text-3xl font-bold text-white">
            {{ $post->title }}
        </h2>


        {{-- Metadata --}}
        <div class="mt-4 flex flex-wrap items-center gap-4 text-sm text-slate-400">

            @if ($post->category)

                <span>
                    Category:
                    <span class="text-slate-300">
                        {{ $post->category->name }}
                    </span>
                </span>

            @endif

            @if ($post->user)

                <span>
                    Author:
                    <span class="text-slate-300">
                        {{ $post->user->name }}
                    </span>
                </span>

            @endif

            <span>
                Status:

                @if ($post->status->value === 'published')

                    <span class="text-stone-600">
                        Published
                    </span>

                @else

                    <span class="text-stone-600">
                        Draft
                    </span>

                @endif

            </span>

            <span>
                {{ $post->created_at->format('F d, Y') }}
            </span>

        </div>


        <div class="my-6 border-t border-slate-800"></div>


        {{-- Featured Image --}}
        @if ($post->featured_image)

            <div class="mb-8">

                <img
                    src="{{ $post->featured_image }}"
                    alt="{{ $post->title }}"
                    class="w-full max-h-125 rounded-xl object-cover"
                >

            </div>

        @endif


        {{-- Excerpt --}}
        <div>

            <h3 class="text-sm font-semibold text-slate-300">
                Excerpt
            </h3>

            <p class="mt-2 text-slate-400">

                {{ $post->excerpt ?: 'No excerpt provided.' }}

            </p>

        </div>


        {{-- Content --}}
        <div class="mt-8">

            <h3 class="text-sm font-semibold text-slate-300">
                Content
            </h3>

            <div class="mt-3 whitespace-pre-line leading-7 text-slate-300">

                {{ $post->content }}

            </div>

        </div>


        {{-- Tags --}}
        @if ($post->tags->count())

            <div class="mt-8">

                <h3 class="text-sm font-semibold text-slate-300">
                    Tags
                </h3>

                <div class="mt-3 flex flex-wrap gap-2">

                    @foreach ($post->tags as $tag)

                        <span class="rounded-full bg-slate-800 px-3 py-1
                                     text-xs text-slate-300">
                            #{{ $tag->name }}
                        </span>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- SEO --}}
        @if ($post->meta_title || $post->meta_description)

            <div class="mt-8 border-t border-slate-800 pt-6">

                <h3 class="text-sm font-semibold text-slate-300">
                    SEO
                </h3>

                @if ($post->meta_title)

                    <div class="mt-3">

                        <p class="text-xs text-slate-500">
                            Meta Title
                        </p>

                        <p class="mt-1 text-sm text-slate-300">
                            {{ $post->meta_title }}
                        </p>

                    </div>

                @endif

                @if ($post->meta_description)

                    <div class="mt-4">

                        <p class="text-xs text-slate-500">
                            Meta Description
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-400">
                            {{ $post->meta_description }}
                        </p>

                    </div>

                @endif

            </div>

        @endif


        {{-- Actions --}}
        <div class="mt-8 flex gap-3 border-t border-slate-800 pt-6">

            <a
                href="{{ route('admin.posts.edit', $post) }}"
                class="rounded-lg bg-orange-600 px-5 py-2.5
                       text-sm font-medium text-white
                       transition hover:bg-orange-600">
                Edit Post
            </a>

            <form
                action="{{ route('admin.posts.destroy', $post) }}"
                method="POST"
                onsubmit="return confirm('Are you sure you want to delete this post?');"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="rounded-lg border border-stone-900
                           px-5 py-2.5 text-sm font-medium
                           text-orange-600 transition
                           hover:bg-stone-100">
                    Delete Post
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
