@extends('layouts.admin')

@section('content')

<div class="space-y-6">


<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Categories
        </h1>

        <p class="mt-1 text-sm text-stone-600">
            Manage your blog categories.
        </p>
    </div>

    <a href="{{ route('admin.categories.create') }}"
       class="rounded-lg bg-orange-600 px-5 py-2.5 text-sm
              font-medium text-stone-900 transition hover:bg-orange-600">
        + Add Category
    </a>
</div>

<div class="overflow-x-auto rounded-xl border border-stone-300 bg-white">

    <table class="w-full text-left">
        <thead class="border-b border-stone-300 bg-white">
            <tr>
                <th class="px-6 py-4 text-sm font-medium text-stone-600">
                    Name
                </th>

                <th class="px-6 py-4 text-sm font-medium text-stone-600">
                    Slug
                </th>

                <th class="px-6 py-4 text-sm font-medium text-stone-600">
                    Description
                </th>

                <th class="px-6 py-4 text-right text-sm font-medium text-stone-600">
                    Actions
                </th>
            </tr>
        </thead>

        <tbody class="divide-y divide-slate-800">

            @forelse ($categories as $category)

                <tr class="hover:bg-slate-800/50">

                    <td class="px-6 py-4 text-sm font-medium text-stone-900">
                        {{ $category->name }}
                    </td>

                    <td class="px-6 py-4 text-sm text-stone-600">
                        {{ $category->slug }}
                    </td>

                    <td class="px-6 py-4 text-sm text-stone-600">
                        {{ $category->description ?? '—' }}
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">

                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="rounded-lg border border-stone-300
                                      px-3 py-2 text-sm text-stone-800
                                      transition hover:bg-slate-800">
                                Edit
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this category?');">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="rounded-lg border border-stone-300
                                           px-3 py-2 text-sm text-orange-600
                                           transition hover:bg-stone-900">
                                    Delete
                                </button>

                            </form>

                        </div>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="4"
                        class="px-6 py-10 text-center text-sm text-stone-600">
                        No categories found.
                    </td>
                </tr>

            @endforelse

        </tbody>
    </table>

</div>


</div>

@endsection
