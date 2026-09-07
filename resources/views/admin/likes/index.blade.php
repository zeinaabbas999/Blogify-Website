@extends('layouts.admin')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="border-b border-stone-300 pb-6"><p class="eyebrow">Likes</p><h1 class="mt-2 font-serif text-4xl font-bold">Posts you liked</h1><p class="mt-2 text-stone-600">A personal list of published stories you have liked.</p></div>
    <div class="divide-y divide-stone-300">
        @forelse ($posts as $post)
            <article class="grid gap-2 py-5 sm:grid-cols-[1fr_auto] sm:items-center sm:gap-8">
                <div><a href="{{ route('posts.show', $post) }}" class="font-semibold text-stone-900 hover:text-orange-600 hover:underline">{{ $post->title }}</a><p class="mt-1 text-sm text-stone-500">By {{ $post->user->name }} · Liked {{ $post->pivot->created_at->format('M j, Y') }}</p></div>
                <a href="{{ route('posts.show', $post) }}" class="text-sm font-semibold text-orange-600 hover:underline">View post</a>
            </article>
        @empty
            <div class="py-14"><p class="font-serif text-xl font-bold">No liked posts yet.</p><p class="mt-1 text-stone-600">Use the heart on a published post to save it here.</p></div>
        @endforelse
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection
