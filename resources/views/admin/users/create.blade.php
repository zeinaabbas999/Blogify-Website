@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Create User
        </h1>

        <p class="mt-1 text-sm text-slate-400">
            Create a new user and assign their role.
        </p>
    </div>

    @if($errors->any())

        <div class="rounded-lg border border-stone-900 bg-stone-100 px-4 py-3">

            <ul class="list-disc space-y-1 pl-5 text-sm text-orange-600">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('admin.users.store') }}"
          method="POST"
          class="space-y-6">

        @csrf

        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6 space-y-6">

            <div>

                <label for="name"
                       class="block text-sm font-medium text-slate-300">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white outline-none focus:border-orange-600"
                >

            </div>

            <div>

                <label for="email"
                       class="block text-sm font-medium text-slate-300">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white outline-none focus:border-orange-600"
                >

            </div>

            <div>

                <label for="role"
                       class="block text-sm font-medium text-slate-300">
                    Role
                </label>

                <select
                    id="role"
                    name="role"
                    required
                    class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white outline-none focus:border-orange-600"
                >

                    <option value="">
                        Select a role
                    </option>

                    @foreach($roles as $role)

                        <option value="{{ $role->name }}"
                            {{ old('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="grid gap-6 md:grid-cols-2">

                <div>

                    <label for="password"
                           class="block text-sm font-medium text-slate-300">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white outline-none focus:border-orange-600"
                    >

                </div>

                <div>

                    <label for="password_confirmation"
                           class="block text-sm font-medium text-slate-300">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="mt-2 w-full rounded-lg border border-slate-700 bg-slate-950 px-4 py-2.5 text-white outline-none focus:border-orange-600"
                    >

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg border border-slate-700 px-5 py-2.5 text-sm font-medium text-slate-300 hover:bg-slate-800">
                Cancel
            </a>

            <button type="submit"
                    class="rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-orange-600">
                Create User
            </button>

        </div>

    </form>

</div>

@endsection