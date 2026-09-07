<x-public-layout :title="$post->meta_title ?: $post->title">
    <div class="mx-auto max-w-3xl px-5 pt-8"><x-flash-messages /></div>
    <article class="mx-auto max-w-3xl px-5 py-14">
        <a href="{{ route('home') }}" class="text-sm font-semibold text-orange-600 hover:underline">Back to all stories</a>
        <div class="mt-10 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs font-bold uppercase tracking-widest text-orange-600">
            <span>{{ $post->category?->name ?? 'Uncategorized' }}</span>
            @foreach ($post->tags as $tag)<span class="text-stone-500">#{{ $tag->name }}</span>@endforeach
        </div>
        <h1 class="mt-3 font-serif text-4xl font-bold leading-tight sm:text-6xl">{{ $post->title }}</h1>
        <p class="mt-6 border-b border-stone-300 pb-6 text-stone-600">By {{ $post->user->name }} · {{ $post->published_at->format('F j, Y') }}</p>
        @if ($post->featured_image)<figure class="mt-8 overflow-hidden bg-stone-200"><img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="max-h-[34rem] w-full object-cover"></figure>@endif
        @if ($post->excerpt)<p class="mt-8 font-serif text-xl leading-8 text-stone-700">{{ $post->excerpt }}</p>@endif
        <div class="mt-8 whitespace-pre-line text-lg leading-8 text-stone-800">{{ $post->content }}</div>

        <div class="mt-10 flex items-center gap-5 border-y border-stone-300 py-5">
            @auth
                @if ($likedByCurrentUser)
                    <form method="POST" action="{{ route('posts.likes.destroy', $post) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" aria-label="Unlike post" class="inline-flex items-center gap-2 rounded-sm text-orange-600 hover:text-orange-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-600">
                            <svg class="size-6" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" /></svg><span class="text-sm font-semibold">{{ $post->liked_by_users_count }}</span>
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('posts.likes.store', $post) }}">
                        @csrf
                        <button type="submit" aria-label="Like post" class="inline-flex items-center gap-2 rounded-sm text-stone-600 hover:text-orange-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-600">
                            <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" /></svg><span class="text-sm font-semibold">{{ $post->liked_by_users_count }}</span>
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" aria-label="Log in to like post" class="inline-flex items-center gap-2 rounded-sm text-stone-600 hover:text-orange-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-orange-600"><svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78Z" /></svg><span class="text-sm font-semibold">{{ $post->liked_by_users_count }}</span></a>
            @endauth
            <a href="#comments-heading" class="text-sm font-medium text-stone-600 hover:text-orange-600">{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }}</a>
        </div>

        <section class="mt-12" aria-labelledby="comments-heading">
            <h2 id="comments-heading" class="font-serif text-3xl font-bold">Comments</h2>

            @auth
                <form method="POST" action="{{ route('comments.store', $post) }}" class="mt-6">
                    @csrf
                    <label for="comment-content" class="block text-sm font-bold text-stone-800">Add a comment</label>
                    <textarea id="comment-content" name="content" rows="4" maxlength="2000" required class="form-control mt-2" placeholder="Share your thoughts...">{{ old('content') }}</textarea>
                    @error('content')<p class="mt-2 text-sm font-semibold text-orange-600">{{ $message }}</p>@enderror
                    <button type="submit" class="btn-primary mt-3">Post comment</button>
                </form>
            @else
                <p class="mt-4 text-stone-600"><a href="{{ route('login') }}" class="font-semibold text-orange-600 hover:underline">Log in</a> to join the conversation.</p>
            @endauth

            <div class="mt-8 divide-y divide-stone-300 border-t border-stone-300">
                @forelse ($post->comments as $comment)
                    <article class="py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-stone-900">{{ $comment->user->name }}</p>
                                <time class="text-sm text-stone-500" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->format('M j, Y \a\t g:i A') }}</time>
                            </div>
                            @can('delete', $comment)
                                <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-stone-600 hover:text-stone-950 hover:underline">Delete</button>
                                </form>
                            @endcan
                        </div>
                        <p class="mt-3 whitespace-pre-line leading-7 text-stone-700">{{ $comment->content }}</p>
                    </article>
                @empty
                    <p class="py-8 text-stone-600">No comments yet.</p>
                @endforelse
            </div>
        </section>
    </article>
</x-public-layout>
