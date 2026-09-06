@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'border border-stone-300 bg-stone-50 px-4 py-3 text-sm font-medium text-stone-900']) }}>
        {{ $status }}
    </div>
@endif
