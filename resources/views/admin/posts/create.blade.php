@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    {{-- Page header --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Create Post
            </h1>

            <p class="mt-1 text-sm text-stone-600">
                Create a new blog post.
            </p>
        </div>

        <a href="{{ route('admin.posts.index') }}"
           class="rounded-lg border border-stone-300 px-4 py-2.5
                  text-sm font-medium text-stone-800 transition
                  hover:bg-slate-800 hover:underline">
            Back to Posts
        </a>

    </div>


    {{-- Validation errors --}}
    @if ($errors->any())

        <div class="rounded-lg border border-stone-300 bg-stone-100 p-4">

            <ul class="space-y-1 text-sm text-orange-600">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Form --}}
    <div class="rounded-xl border border-stone-300 bg-white p-6">

        <form action="{{ route('admin.posts.store') }}"
              method="POST"
              class="space-y-6">

            @csrf


            {{-- Title --}}
            <div>

                <label for="title"
                       class="block text-sm font-medium text-stone-800">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    placeholder="Enter post title"
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-2.5 text-stone-900
                           placeholder-stone-500 outline-none
                           focus:border-orange-600"
                >

                @error('title')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Excerpt --}}
            <div>

                <label for="excerpt"
                       class="block text-sm font-medium text-stone-800">
                    Excerpt
                </label>

                <textarea
                    id="excerpt"
                    name="excerpt"
                    rows="3"
                    placeholder="Short description of the post..."
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-3 text-stone-900
                           placeholder-stone-500 outline-none
                           focus:border-orange-600"
                >{{ old('excerpt') }}</textarea>

                @error('excerpt')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Content --}}
            <div>

                <label for="content"
                       class="block text-sm font-medium text-stone-800">
                    Content
                </label>

                <textarea
                    id="content"
                    name="content"
                    rows="12"
                    placeholder="Write your post..."
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-3 text-stone-900
                           placeholder-stone-500 outline-none
                           focus:border-orange-600"
                >{{ old('content') }}</textarea>

                @error('content')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Category --}}
            <div>

                <label for="category_id"
                       class="block text-sm font-medium text-stone-800">
                    Category
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-2.5 text-stone-900
                           outline-none focus:border-orange-600"
                >

                    <option value="">
                        No category
                    </option>

                    @foreach ($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

                @error('category_id')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Tags --}}
            <div>

                <label for="tags"
                       class="block text-sm font-medium text-stone-800">
                    Tags
                </label>

                <select
                    id="tags"
                    name="tags[]"
                    multiple
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-3 text-stone-900
                           outline-none focus:border-orange-600"
                >

                    @foreach ($tags as $tag)

                        <option
                            value="{{ $tag->id }}"
                            {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}
                        >
                            {{ $tag->name }}
                        </option>

                    @endforeach

                </select>

                <p class="mt-1 text-xs text-stone-600">
                    Hold Ctrl while clicking to select multiple tags.
                </p>

                @error('tags')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Status --}}
            <div>

                <label for="status"
                       class="block text-sm font-medium text-stone-800">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-2.5 text-stone-900
                           outline-none focus:border-orange-600"
                >

                    <option
                        value="draft"
                        {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}
                    >
                        Draft
                    </option>

                    @can('publish_posts')
                    <option
                        value="published"
                        {{ old('status') === 'published' ? 'selected' : '' }}
                    >
                        Published
                    </option>
                    @endcan

                </select>

                @error('status')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- Featured Image --}}
            <div>

                <label for="featured_image"
                       class="block text-sm font-medium text-stone-800">
                    Featured Image
                </label>

                <input
                    type="text"
                    id="featured_image"
                    name="featured_image"
                    value="{{ old('featured_image') }}"
                    placeholder="Image URL or path"
                    class="mt-2 w-full rounded-lg border border-stone-300
                           bg-white px-4 py-2.5 text-stone-900
                           placeholder-stone-500 outline-none
                           focus:border-orange-600"
                >

                <p class="mt-1 text-xs text-stone-600">
                    Enter an image URL or path for now.
                </p>

                @error('featured_image')
                    <p class="mt-1 text-sm text-orange-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- SEO --}}
            <div class="border-t border-stone-300 pt-6">

                <h2 class="text-lg font-semibold text-stone-900">
                    SEO
                </h2>

                <p class="mt-1 text-sm text-stone-600">
                    Search engine metadata for this post.
                </p>


                {{-- Meta title --}}
                <div class="mt-5">

                    <label for="meta_title"
                           class="block text-sm font-medium text-stone-800">
                        Meta Title
                    </label>

                    <input
                        type="text"
                        id="meta_title"
                        name="meta_title"
                        value="{{ old('meta_title') }}"
                        placeholder="SEO title"
                        class="mt-2 w-full rounded-lg border border-stone-300
                               bg-white px-4 py-2.5 text-stone-900
                               placeholder-stone-500 outline-none
                               focus:border-orange-600"
                    >

                    @error('meta_title')
                        <p class="mt-1 text-sm text-orange-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- Meta description --}}
                <div class="mt-5">

                    <label for="meta_description"
                           class="block text-sm font-medium text-stone-800">
                        Meta Description
                    </label>

                    <textarea
                        id="meta_description"
                        name="meta_description"
                        rows="4"
                        placeholder="SEO description..."
                        class="mt-2 w-full rounded-lg border border-stone-300
                               bg-white px-4 py-3 text-stone-900
                               placeholder-stone-500 outline-none
                               focus:border-orange-600"
                    >{{ old('meta_description') }}</textarea>

                    @error('meta_description')
                        <p class="mt-1 text-sm text-orange-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>


            {{-- Buttons --}}
            <div class="flex gap-3 border-t border-stone-300 pt-6">

                <button
                    type="submit"
                    class="rounded-lg bg-orange-600 px-5 py-2.5
                           text-sm font-medium text-stone-900
                           transition hover:bg-orange-600">
                    Create Post
                </button>

                <a
                    href="{{ route('admin.posts.index') }}"
                    class="rounded-lg border border-stone-300
                           px-5 py-2.5 text-sm font-medium
                           text-stone-800 transition
                           hover:bg-slate-800 hover:underline">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>
@endsection
