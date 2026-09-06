<x-public-layout :title="$post->meta_title ?: $post->title">
    <article class="mx-auto max-w-3xl px-5 py-14">
        <a href="{{ route('home') }}" class="text-sm font-semibold text-orange-600 hover:underline">Back to all stories</a>
        <p class="mt-10 text-xs font-bold uppercase tracking-widest text-orange-600">{{ $post->category?->name ?? 'Uncategorized' }}</p>
        <h1 class="mt-3 font-serif text-4xl font-bold leading-tight sm:text-6xl">{{ $post->title }}</h1>
        <p class="mt-6 border-b border-stone-300 pb-6 text-stone-600">By {{ $post->user->name }} · {{ $post->published_at->format('F j, Y') }}</p>
        @if ($post->excerpt)<p class="mt-8 font-serif text-xl leading-8 text-stone-700">{{ $post->excerpt }}</p>@endif
        <div class="mt-8 whitespace-pre-line text-lg leading-8 text-stone-800">{{ $post->content }}</div>
    </article>
</x-public-layout>
