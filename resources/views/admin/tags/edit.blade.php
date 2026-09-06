@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Edit Tag
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Update this tag.
        </p>
    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <form action="{{ route('admin.tags.update', $tag) }}"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')

            <div>
                <label for="name"
                       class="block text-sm font-medium text-slate-300">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $tag->name) }}"
                    class="mt-2 w-full rounded-lg border border-slate-700
                           bg-slate-950 px-4 py-2.5 text-white
                           outline-none focus:border-indigo-500"
                >

                @error('name')
                    <p class="mt-1 text-sm text-red-400">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5
                           text-sm font-medium text-white
                           hover:bg-indigo-500">
                    Update Tag
                </button>

                <a
                    href="{{ route('admin.tags.index') }}"
                    class="rounded-lg border border-slate-700
                           px-5 py-2.5 text-sm font-medium
                           text-slate-300 hover:bg-slate-800">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection