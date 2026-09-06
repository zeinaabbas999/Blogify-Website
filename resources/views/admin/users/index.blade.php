@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="page-heading">
        <div>
            <p class="eyebrow">Administration</p>
            <h1>Users</h1>
            <p>Manage accounts and their assigned roles.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">Add user</a>
    </div>

    <div class="mt-8 overflow-hidden border border-stone-300 bg-white">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[720px] text-left">
                <thead class="border-b border-stone-300 bg-stone-50">
                    <tr class="text-xs font-bold uppercase tracking-wider text-stone-600">
                        <th class="px-5 py-3.5">User</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-200">
                    @forelse ($users as $user)
                        <tr class="hover:bg-stone-50">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-stone-200 text-sm font-bold text-stone-800">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-stone-900">{{ $user->name }}</p>
                                        @if ($user->is(auth()->user()))
                                            <span class="text-xs text-stone-500">Current account</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-sm text-stone-700">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @forelse ($user->roles as $role)
                                    <span class="inline-flex border border-stone-300 bg-stone-50 px-2.5 py-1 text-xs font-semibold text-stone-700">{{ $role->name }}</span>
                                @empty
                                    <span class="text-sm text-stone-500">No role</span>
                                @endforelse
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4 text-sm font-semibold">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-stone-700 hover:text-stone-950 hover:underline">View</a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-orange-600 hover:underline">Edit</a>
                                    @if (! $user->is(auth()->user()))
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-stone-700 hover:text-stone-950 hover:underline">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-14 text-center text-sm text-stone-600">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
