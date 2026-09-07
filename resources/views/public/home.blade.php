<x-public-layout>
    <div class="mx-auto max-w-6xl px-5 py-12">
        <div class="max-w-2xl border-b border-stone-300 pb-6">
            <p class="mb-3 text-sm font-bold uppercase tracking-widest text-orange-600">Latest publishing</p>
            <h1 class="font-serif text-4xl font-bold leading-tight sm:text-5xl">Stories, reporting, and ideas worth reading.</h1>
        </div>

        @if ($posts->isEmpty())
            <div class="py-20">
                <h2 class="font-serif text-2xl font-bold">No stories have been published yet.</h2>
                <p class="mt-2 text-stone-600">Please check back soon.</p>
            </div>
        @else
            <div class="mt-10 grid gap-x-8 gap-y-12 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($posts as $post)
                    <article class="border-t border-stone-400 pt-5">
                        @if ($post->featured_image)<a href="{{ route('posts.show', $post) }}" class="mb-5 block overflow-hidden bg-stone-200"><img src="{{ $post->featured_image }}" alt="" class="aspect-[16/9] w-full object-cover transition duration-300 hover:scale-[1.02]" loading="lazy"></a>@endif
                        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">{{ $post->category?->name ?? 'Uncategorized' }}</p>
                        <h2 class="mt-3 font-serif text-2xl font-bold leading-snug"><a class="hover:text-orange-600" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h2>
                        @if ($post->excerpt)<p class="mt-3 leading-7 text-stone-600">{{ $post->excerpt }}</p>@endif
                        <p class="mt-5 text-sm text-stone-500">{{ $post->user->name }} · {{ $post->published_at->format('M j, Y') }}</p>
                        <p class="mt-2 flex items-center gap-3 text-xs font-medium text-stone-500"><span class="inline-flex items-center gap-1"><svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" /></svg>{{ $post->liked_by_users_count }}</span><span>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</span></p>
                    </article>
                @endforeach
            </div>
            <div class="mt-12">{{ $posts->links() }}</div>
        @endif
    </div>
</x-public-layout>
