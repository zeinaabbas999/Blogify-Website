<x-public-layout>
    <div class="mx-auto max-w-6xl px-5 py-12">
        <div class="max-w-2xl border-b-4 border-stone-900 pb-6">
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
                        <p class="text-xs font-bold uppercase tracking-wider text-orange-600">{{ $post->category?->name ?? 'Uncategorized' }}</p>
                        <h2 class="mt-3 font-serif text-2xl font-bold leading-snug"><a class="hover:text-orange-600" href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h2>
                        @if ($post->excerpt)<p class="mt-3 leading-7 text-stone-600">{{ $post->excerpt }}</p>@endif
                        <p class="mt-5 text-sm text-stone-500">{{ $post->user->name }} · {{ $post->published_at->format('M j, Y') }}</p>
                    </article>
                @endforeach
            </div>
            <div class="mt-12">{{ $posts->links() }}</div>
        @endif
    </div>
</x-public-layout>
