@extends('layouts.admin')

@section('content')

<div class="space-y-6">

<div>
    <h1 class="text-2xl font-bold text-slate-900">
        Edit Category
    </h1>

    <p class="mt-1 text-sm text-orange-600">
        Update this category.
    </p>
</div>

<div class="rounded-xl border border-stone-300 bg-white p-6">

    <form action="{{ route('admin.categories.update', $category) }}"
          method="POST"
          class="space-y-6">

        @csrf
        @method('PUT')

        <div>
            <label for="name"
                   class="block text-sm font-medium text-stone-800">
                Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $category->name) }}"
                class="mt-2 w-full rounded-lg border border-stone-300
                       bg-white px-4 py-2.5 text-stone-900
                       placeholder-stone-500 outline-none
                       focus:border-orange-600"
            >

            @error('name')
                <p class="mt-1 text-sm text-orange-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label for="description"
                   class="block text-sm font-medium text-stone-800">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
                class="mt-2 w-full rounded-lg border border-stone-300
                       bg-white px-4 py-3 text-stone-900
                       placeholder-stone-500 outline-none
                       focus:border-orange-600"
            >{{ old('description', $category->description) }}</textarea>

            @error('description')
                <p class="mt-1 text-sm text-orange-600">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="flex gap-3">

            <button
                type="submit"
                class="rounded-lg bg-orange-600 px-5 py-2.5
                       text-sm font-medium text-stone-900
                       transition hover:bg-orange-600">
                Update Category
            </button>

            <a
                href="{{ route('admin.categories.index') }}"
                class="rounded-lg border border-stone-300
                       px-5 py-2.5 text-sm font-medium
                       text-stone-800 transition hover:bg-slate-800">
                Cancel
            </a>

        </div>

    </form>

</div>


</div>

@endsection
