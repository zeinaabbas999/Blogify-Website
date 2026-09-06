<x-guest-layout>
    <p class="leading-7 text-stone-700">
        {{ $alreadyVerified ? 'Your email address was already verified.' : 'Your email address has been verified successfully.' }}
        You can return to your original Blogify tab, which will continue automatically.
    </p>

    <div class="mt-6">
        <a href="{{ route('dashboard') }}" class="btn-primary">Open dashboard</a>
    </div>
</x-guest-layout>
