@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Users
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Manage users and their roles.
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}"
           class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-500">
            + Add User
        </a>

    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-950 bg-green-900/30 px-4 py-3 text-sm text-green-950">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-900 bg-red-900/30 px-4 py-3 text-sm text-red-900">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-800 bg-slate-900">

        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="border-b border-slate-800 bg-slate-950">

                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            User
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Email
                        </th>

                        <th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Role
                        </th>

                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-400">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-800">

                    @forelse($users as $user)

                        <tr class="hover:bg-slate-800/40">

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <p class="font-medium text-white">
                                            {{ $user->name }}
                                        </p>

                                        @if($user->id === auth()->id())
                                            <span class="text-xs text-indigo-400">
                                                You
                                            </span>
                                        @endif
                                    </div>

                                </div>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-300">
                                {{ $user->email }}
                            </td>

                            <td class="px-6 py-4">

                                @forelse($user->roles as $role)

                                    <span class="inline-flex rounded-full bg-indigo-500/10 px-3 py-1 text-xs font-medium text-indigo-400">
                                        {{ $role->name }}
                                    </span>

                                @empty

                                    <span class="text-sm text-slate-500">
                                        No role
                                    </span>

                                @endforelse

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a href="{{ route('admin.users.show', $user) }}"
                                       class="rounded-lg px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">
                                        View
                                    </a>

                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="rounded-lg px-3 py-2 text-sm text-indigo-400 hover:bg-indigo-500/10">
                                        Edit
                                    </a>

                                    @if($user->id !== auth()->id())

                                        <form action="{{ route('admin.users.destroy', $user) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this user?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg px-3 py-2 text-sm text-red-400 hover:bg-red-500/10">
                                                Delete
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                No users found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection