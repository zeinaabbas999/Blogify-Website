@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    <div class="flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                User Details
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                View account information and activity.
            </p>
        </div>

        <a href="{{ route('admin.users.edit', $user) }}"
           class="rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-orange-600">
            Edit User
        </a>

    </div>

    <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

        <div class="flex items-center gap-5">

            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-orange-600 text-xl font-bold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>

            <div>

                <h2 class="text-xl font-semibold text-white">
                    {{ $user->name }}
                </h2>

                <p class="text-sm text-slate-400">
                    {{ $user->email }}
                </p>

            </div>

        </div>

        <div class="mt-8 grid gap-6 md:grid-cols-2">

            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">
                    Role
                </p>

                <div class="mt-2">

                    @forelse($user->roles as $role)

                        <span class="inline-flex rounded-full bg-orange-600/10 px-3 py-1 text-sm font-medium text-orange-600">
                            {{ $role->name }}
                        </span>

                    @empty

                        <span class="text-sm text-slate-500">
                            No role assigned
                        </span>

                    @endforelse

                </div>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">
                    Joined
                </p>

                <p class="mt-2 text-sm text-slate-300">
                    {{ $user->created_at->format('M d, Y') }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">
                    Posts
                </p>

                <p class="mt-2 text-sm text-slate-300">
                    {{ $user->posts->count() }}
                </p>
            </div>

            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">
                    Last Updated
                </p>

                <p class="mt-2 text-sm text-slate-300">
                    {{ $user->updated_at->format('M d, Y') }}
                </p>
            </div>

        </div>

    </div>

    <div class="flex justify-start">

        <a href="{{ route('admin.users.index') }}"
           class="rounded-lg border border-slate-900 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-800">
            ← Back to Users
        </a>

    </div>

</div>

@endsection