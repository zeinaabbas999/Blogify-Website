@extends('layouts.admin')

@section('content')

    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Create Category
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Add a new post category.
            </p>
        </div>


        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">

                @csrf

                <div>
                    <label for="name"
                           class="block text-sm font-medium text-slate-300">
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="Enter category name"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500">
                </div>


                <div>
                    <label for="slug"
                           class="block text-sm font-medium text-slate-300">
                        Slug
                    </label>

                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        placeholder="category-slug"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500">
                </div>


                <div>
                    <label for="description"
                           class="block text-sm font-medium text-slate-300">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Describe this category..."
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-3 text-white
                               placeholder-slate-500 outline-none
                               focus:border-indigo-500"
                    ></textarea>
                </div>


                <div class="flex gap-3">

                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5
                                   text-sm font-medium text-white
                                   hover:bg-indigo-500">
                        Create Category
                    </button>

                    <a href="{{ route('admin.categories.index') }}"
                       class="rounded-lg border border-slate-700 px-5 py-2.5
                              text-sm font-medium text-slate-300
                              hover:bg-slate-800 hover:underline">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

@endsection