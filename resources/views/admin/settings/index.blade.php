@extends('layouts.admin')

@section('content')

    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Settings
            </h1>

            <p class="mt-1 text-sm text-slate-400">
                Manage your website's settings.
            </p>
        </div>


        {{-- General Settings --}}
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

            <h2 class="text-lg font-semibold text-white">
                General Settings
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Basic information about your website.
            </p>


            <form action="{{ route('admin.settings.update') }}" method="POST" class="mt-6 space-y-6">

                @csrf
                @method('PUT')

                <div>
                    <label for="site_name"
                           class="block text-sm font-medium text-slate-300">
                        Site Name
                    </label>

                    <input
                        type="text"
                        id="site_name"
                        name="site_name"
                        value="{{ old('site_name', $settings['site_name'] ?? 'CMS') }}"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-2.5 text-white
                               outline-none focus:border-orange-600">
                </div>


                <div>
                    <label for="site_description"
                           class="block text-sm font-medium text-slate-300">
                        Site Description
                    </label>

                    <textarea
                        id="site_description"
                        name="site_description"
                        rows="3"
                        class="mt-2 w-full rounded-lg border border-slate-700
                               bg-slate-950 px-4 py-3 text-white
                               outline-none focus:border-orange-600"
                    >{{ old('site_description', $settings['site_description'] ?? 'Your website description') }}</textarea>
                </div>


                <button type="submit"
                        class="rounded-lg bg-orange-600 px-5 py-2.5
                               text-sm font-medium text-white
                               hover:bg-orange-600">
                    Save Settings
                </button>


            </form>

        </div>


        {{-- Appearance --}}
        <div class="rounded-xl border border-slate-800 bg-slate-900 p-6">

            <h2 class="text-lg font-semibold text-white">
                Appearance
            </h2>

            <p class="mt-1 text-sm text-slate-400">
                Configure the appearance of your website.
            </p>

            <div class="mt-6">

                <label for="theme"
                       class="block text-sm font-medium text-slate-300">
                    Theme
                </label>

                <select
                    id="theme"
                    class="mt-2 w-full rounded-lg border border-slate-700
                           bg-slate-950 px-4 py-2.5 text-white
                           outline-none focus:border-orange-600"
                >
                    <option>Dark</option>
                    <option>Light</option>
                </select>

            </div>

        </div>

    </div>

@endsection