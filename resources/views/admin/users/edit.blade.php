@extends('layouts.admin')

@section('content')

<div class="mx-auto max-w-3xl space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Edit User
        </h1>

        <p class="mt-1 text-sm text-stone-600">
            Update user information and role.
        </p>
    </div>

    @if($errors->any())

        <div class="rounded-lg border border-stone-300 bg-stone-100 px-4 py-3">

            <ul class="list-disc space-y-1 pl-5 text-sm text-orange-600">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('admin.users.update', $user) }}"
          method="POST"
          class="space-y-6">

        @csrf
        @method('PUT')

        <div class="rounded-xl border border-stone-300 bg-white p-6 space-y-6">

            <div>

                <label for="name"
                       class="block text-sm font-medium text-stone-800">
                    Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-stone-900 outline-none focus:border-orange-600"
                >

            </div>

            <div>

                <label for="email"
                       class="block text-sm font-medium text-stone-800">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-stone-900 outline-none focus:border-orange-600"
                >

            </div>

            <div>

                <label for="role"
                       class="block text-sm font-medium text-stone-800">
                    Role
                </label>

                @php
                    $currentRole = $user->roles->first()?->name;
                @endphp

                <select
                    id="role"
                    name="role"
                    required
                    class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-stone-900 outline-none focus:border-orange-600"
                >

                    @foreach($roles as $role)

                        <option value="{{ $role->name }}"
                            {{ old('role', $currentRole) === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="border-t border-stone-300 pt-6">

                <h2 class="text-sm font-semibold text-stone-900">
                    Change Password
                </h2>

                <p class="mt-1 text-sm text-stone-600">
                    Leave these fields empty to keep the current password.
                </p>

                <div class="mt-5 grid gap-6 md:grid-cols-2">

                    <div>

                        <label for="password"
                               class="block text-sm font-medium text-stone-800">
                            New Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-stone-900 outline-none focus:border-orange-600"
                        >

                    </div>

                    <div>

                        <label for="password_confirmation"
                               class="block text-sm font-medium text-stone-800">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="mt-2 w-full rounded-lg border border-stone-300 bg-white px-4 py-2.5 text-stone-900 outline-none focus:border-orange-600"
                        >

                    </div>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3">

            <a href="{{ route('admin.users.index') }}"
               class="rounded-lg border border-slate-900 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-800">
                Cancel
            </a>

            <button type="submit"
                    class="rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-medium text-stone-900 hover:bg-orange-600">
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection