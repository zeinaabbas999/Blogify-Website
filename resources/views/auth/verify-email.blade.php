<x-guest-layout>
    <x-flash-messages />

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    <p id="verification-progress" class="mt-4 border-l-2 border-orange-600 pl-3 text-sm text-stone-600" aria-live="polite">
        This page will continue automatically after your email is verified.
    </p>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-600 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const progress = document.getElementById('verification-progress');
            let polling = true;

            const checkVerification = async () => {
                if (!polling) return;

                try {
                    const response = await fetch(@json(route('verification.status', absolute: false)), {
                        credentials: 'same-origin',
                        headers: { Accept: 'application/json' },
                        cache: 'no-store',
                    });

                    if (response.status === 401) {
                        polling = false;
                        clearInterval(timer);
                        progress.textContent = 'Your session has ended. Log in again to continue.';
                        return;
                    }

                    if (!response.ok) return;

                    const result = await response.json();

                    if (result.verified === true) {
                        polling = false;
                        clearInterval(timer);
                        progress.textContent = 'Email verified. Opening your dashboard…';
                        window.location.assign(@json(route('dashboard', absolute: false)));
                    }
                } catch (error) {
                    // A temporary network failure is retried on the next interval.
                }
            };

            const timer = window.setInterval(checkVerification, 3000);
            checkVerification();
        });
    </script>
</x-guest-layout>
