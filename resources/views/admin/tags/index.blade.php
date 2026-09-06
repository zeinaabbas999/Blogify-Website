@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Tags
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Manage your blog tags.
            </p>
        </div>

        <a href="{{ route('admin.tags.create') }}"
           class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm
                  font-medium text-white transition hover:bg-indigo-500">
            + Add Tag
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">

        <table class="w-full text-left">

            <thead class="border-b border-slate-800 bg-slate-950">
                <tr>
                    <th class="px-6 py-4 text-sm font-medium text-slate-400">
                        Name
                    </th>

                    <th class="px-6 py-4 text-sm font-medium text-slate-400">
                        Slug
                    </th>

                    <th class="px-6 py-4 text-right text-sm font-medium text-slate-400">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-800">

                @forelse ($tags as $tag)

                    <tr class="hover:bg-slate-800/50">

                        <td class="px-6 py-4 text-sm font-medium text-white">
                            {{ $tag->name }}
                        </td>

                        <td class="px-6 py-4 text-sm text-slate-400">
                            {{ $tag->slug }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">

                                <a href="{{ route('admin.tags.edit', $tag) }}"
                                   class="rounded-lg border border-slate-700
                                          px-3 py-2 text-sm text-slate-300
                                          hover:bg-slate-800">
                                    Edit
                                </a>

                                <form action="{{ route('admin.tags.destroy', $tag) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this tag?');">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="rounded-lg border border-red-900
                                               px-3 py-2 text-sm text-red-400
                                               hover:bg-red-950">
                                        Delete
                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3"
                            class="px-6 py-10 text-center text-sm text-slate-500">
                            No tags found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection