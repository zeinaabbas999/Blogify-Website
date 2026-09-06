@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-bold text-stone-800']) }}>
    {{ $value ?? $slot }}
</label>
