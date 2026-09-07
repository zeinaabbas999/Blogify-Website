@php
    $statusMessages = [
        'profile-updated' => 'Profile information updated successfully.',
        'password-updated' => 'Password updated successfully.',
    ];

    $messages = collect([
        ['type' => 'success', 'message' => session('success')],
        ['type' => 'error', 'message' => session('error')],
        ['type' => 'warning', 'message' => session('warning')],
        ['type' => 'info', 'message' => $statusMessages[session('status')] ?? session('status')],
    ])->filter(fn (array $item) => filled($item['message']));
@endphp

@if ($messages->isNotEmpty())
    <div class="mb-6 space-y-3" aria-live="polite">
        @foreach ($messages as $item)
            <div class="flash-message flash-message-{{ $item['type'] }}" role="{{ $item['type'] === 'error' ? 'alert' : 'status' }}">
                {{ $item['message'] }}
            </div>
        @endforeach
    </div>
@endif
