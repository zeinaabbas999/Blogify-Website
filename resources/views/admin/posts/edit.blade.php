@extends('layouts.admin')

@section('content')

    <div class="space-y-6">

        {{-- Page header --}}
        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Edit Post
                </h1>

                <p class="mt-1 text-sm text-slate-400">
                    Update your blog post.
                </p>
            </div>

            <a href="{{ route('admin.posts.index') }}"
               class="rounded-lg border border-slate-900 px-4 py-2.5
                      text-sm font-medium text-slate-700 transition
                      hover:bg-slate-800 hover:underline">
                Back to Posts
            </a>

        </div>


        {{-- Validation errors --}}
        @if ($errors->any())

            <div class="rounded-lg border border-red-800 bg-red-900/30 p-4">

                <ul class="space-y-1 text-sm text-red-400">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- Form --}}
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

            <form action="{{ route('admin.posts.update', $post) }}"
                  method="POST"
                  class="space-y-6">

                @csrf
                @method('PUT')


                {{-- Title --}}
                <div>

                    <label for="title"
                           class="block text-sm font-medium text-slate-300">
                        Title
                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title', $post->title) }}"
                        placeholder="Enter post title"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500"
                    >

                    @error('title')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Slug --}}
                <div>

                    <label for="slug"
                           class="block text-sm font-medium text-slate-300">
                        Slug
                    </label>

                    <input
                        type="text"
                        id="slug"
                        value="{{ $post->slug }}"
                        readonly
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-900 px-4 py-2.5 text-slate-500
                               outline-none cursor-not-allowed"
                    >

                    <p class="mt-1 text-xs text-slate-500">
                        The slug is automatically generated from the post title.
                    </p>

                </div>


                {{-- Excerpt --}}
                <div>

                    <label for="excerpt"
                           class="block text-sm font-medium text-slate-300">
                        Excerpt
                    </label>

                    <textarea
                        id="excerpt"
                        name="excerpt"
                        rows="3"
                        placeholder="Short description of the post..."
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-3 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500"
                    >{{ old('excerpt', $post->excerpt) }}</textarea>

                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Content --}}
                <div>

                    <label for="content"
                           class="block text-sm font-medium text-slate-300">
                        Content
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="12"
                        placeholder="Write your post..."
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-3 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500"
                    >{{ old('content', $post->content) }}</textarea>

                    @error('content')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Category --}}
                <div>

                    <label for="category_id"
                           class="block text-sm font-medium text-slate-300">
                        Category
                    </label>

                    <select
                        id="category_id"
                        name="category_id"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               outline-none focus:border-indigo-500"
                    >

                        <option value="">
                            No category
                        </option>

                        @foreach ($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                    @error('category_id')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Tags --}}
                <div>

                    <label for="tags"
                           class="block text-sm font-medium text-slate-300">
                        Tags
                    </label>

                    @php
                        $selectedTags = old(
                            'tags',
                            $post->tags->pluck('id')->toArray()
                        );
                    @endphp

                    <select
                        id="tags"
                        name="tags[]"
                        multiple
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-3 text-white
                               outline-none focus:border-indigo-500"
                    >

                        @foreach ($tags as $tag)

                            <option
                                value="{{ $tag->id }}"
                                {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}
                            >
                                {{ $tag->name }}
                            </option>

                        @endforeach

                    </select>

                    <p class="mt-1 text-xs text-slate-500">
                        Hold Ctrl while clicking to select multiple tags.
                    </p>

                    @error('tags')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Status --}}
                <div>

                    <label for="status"
                           class="block text-sm font-medium text-slate-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               outline-none focus:border-indigo-500"
                    >

                        <option
                            value="draft"
                            {{ old('status', $post->status->value) === 'draft' ? 'selected' : '' }}
                        >
                            Draft
                        </option>

                        <option
                            value="published"
                            {{ old('status', $post->status->value) === 'published' ? 'selected' : '' }}
                        >
                            Published
                        </option>

                    </select>

                    @error('status')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Featured Image --}}
                <div>

                    <label for="featured_image"
                           class="block text-sm font-medium text-slate-300">
                        Featured Image
                    </label>

                    <input
                        type="text"
                        id="featured_image"
                        name="featured_image"
                        value="{{ old('featured_image', $post->featured_image) }}"
                        placeholder="Image URL or path"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500"
                    >

                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-400">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- SEO --}}
                <div class="border-t border-slate-800 pt-6">

                    <h2 class="text-lg font-semibold text-white">
                        SEO
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Search engine metadata for this post.
                    </p>


                    {{-- Meta title --}}
                    <div class="mt-5">

                        <label for="meta_title"
                               class="block text-sm font-medium text-slate-300">
                            Meta Title
                        </label>

                        <input
                            type="text"
                            id="meta_title"
                            name="meta_title"
                            value="{{ old('meta_title', $post->meta_title) }}"
                            placeholder="SEO title"
                            class="mt-2 w-full rounded-lg border border-slate-700
                                   bg-slate-950 px-4 py-2.5 text-white
                                   placeholder-slate-500 outline-none
                                   focus:border-indigo-500"
                        >

                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- Meta description --}}
                    <div class="mt-5">

                        <label for="meta_description"
                               class="block text-sm font-medium text-slate-300">
                            Meta Description
                        </label>

                        <textarea
                            id="meta_description"
                            name="meta_description"
                            rows="4"
                            placeholder="SEO description..."
                            class="mt-2 w-full rounded-lg border border-slate-700
                                   bg-slate-950 px-4 py-3 text-white
                                   placeholder-slate-500 outline-none
                                   focus:border-indigo-500"
                        >{{ old('meta_description', $post->meta_description) }}</textarea>

                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-400">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="flex gap-3 border-t border-slate-800 pt-6">

                    <button
                        type="submit"
                        class="rounded-lg bg-indigo-600 px-5 py-2.5
                               text-sm font-medium text-white
                               transition hover:bg-indigo-500">
                        Update Post
                    </button>

                    <a
                        href="{{ route('admin.posts.show', $post) }}"
                        class="rounded-lg border border-slate-700
                               px-5 py-2.5 text-sm font-medium
                               text-slate-300 transition
                               hover:bg-slate-800 hover:underline">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection