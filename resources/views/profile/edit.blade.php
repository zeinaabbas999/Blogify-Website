@extends('layouts.admin')

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="page-heading"><div><p class="eyebrow">Account</p><h1>Profile settings</h1><p>Manage your identity, password, and account.</p></div></div>
        <div class="mt-8 grid gap-8 lg:grid-cols-2">
        <section class="border border-stone-300 bg-white p-6 sm:p-8">@include('profile.partials.update-profile-information-form')</section>
        <section class="border border-stone-300 bg-white p-6 sm:p-8">@include('profile.partials.update-password-form')</section>
        <section class="border border-stone-300 bg-white p-6 sm:p-8 lg:col-span-2">@include('profile.partials.delete-user-form')</section>
        </div>
    </div>
@endsection
