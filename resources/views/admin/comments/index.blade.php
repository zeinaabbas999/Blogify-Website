@extends('layouts.admin')
@section('content')
<div class="mx-auto max-w-5xl">
    <div class="border-b border-stone-300 pb-6"><p class="eyebrow">Comments</p><h1 class="mt-2 font-serif text-4xl font-bold">{{ $canModerate ? 'All comments' : 'Your comments' }}</h1><p class="mt-2 text-stone-600">{{ $canModerate ? 'Review recent discussion across published posts.' : 'Comments you have shared on published posts.' }}</p></div>
    <div class="divide-y divide-stone-300">
        @forelse ($comments as $comment)
            <article class="grid gap-3 py-5 sm:grid-cols-[1fr_auto] sm:items-start sm:gap-8">
                <div class="min-w-0">
                    @if ($canModerate)<p class="text-sm font-semibold text-stone-900">{{ $comment->user->name }}</p>@endif
                    <p class="mt-1 text-stone-700">{{ Str::limit($comment->content, 180) }}</p>
                    <p class="mt-2 text-sm text-stone-500">On: <a href="{{ route('posts.show', $comment->post) }}" class="font-semibold hover:text-orange-600 hover:underline">{{ $comment->post->title }}</a> · {{ $comment->created_at->format('M j, Y') }}</p>
                </div>
                @can('delete', $comment)
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?');">@csrf @method('DELETE')<button type="submit" class="text-sm font-semibold text-stone-600 hover:text-stone-950 hover:underline">Delete</button></form>
                @endcan
            </article>
        @empty
            <div class="py-14"><p class="font-serif text-xl font-bold">No comments yet.</p><p class="mt-1 text-stone-600">Comments on published posts will appear here.</p></div>
        @endforelse
    </div>
    <div class="mt-8">{{ $comments->links() }}</div>
</div>
@endsection
