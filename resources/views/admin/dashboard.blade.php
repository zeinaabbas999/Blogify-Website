@extends('layouts.admin')
@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-5 border-b border-stone-300 pb-6"><div><p class="eyebrow">Workspace</p><h1 class="mt-2 font-serif text-4xl font-bold">Dashboard</h1><p class="mt-2 text-stone-600">Welcome back, {{ auth()->user()->name }}.</p></div>@can('create_posts')<a href="{{ route('admin.posts.create') }}" class="btn-primary">Create a post</a>@endcan</div>
    <dl class="mt-7 grid gap-4 sm:grid-cols-2">
        @foreach ([['Total posts', $postsCount], ['Published', $publishedPosts], ['Drafts', $draftPosts], [$fourthMetricLabel, $fourthMetricValue]] as [$label, $value])
            <div class="min-h-32 rounded-md border border-stone-300 bg-white p-5">
                <dt class="text-sm font-semibold text-stone-600">{{ $label }}</dt>
                <dd class="mt-3 font-serif text-4xl font-bold text-stone-950">{{ $value }}</dd>
            </div>
        @endforeach
    </dl>
    <section class="mt-10" aria-labelledby="recent-activity-heading">
        <div class="border-b border-stone-400 pb-3"><h2 id="recent-activity-heading" class="font-serif text-2xl font-bold">Recent Activity</h2></div>
        <div class="divide-y divide-stone-300">
            @forelse ($recentActivity as $activity)
                <article class="py-4">
                    <p class="font-semibold text-stone-900">
                        <span class="font-bold">{{ $activity['actor']->is(auth()->user()) ? 'You' : $activity['actor']->name }}</span>
                        @if ($activity['type'] === 'commented')
                            commented on
                        @elseif ($activity['type'] === 'liked')
                            liked
                        @elseif ($activity['type'] === 'published')
                            published
                        @else
                            created a draft
                        @endif
                        “<a href="{{ $activity['type'] === 'created' ? route('admin.posts.show', $activity['post']) : route('posts.show', $activity['post']) }}" class="hover:text-orange-600 hover:underline">{{ $activity['post']->title }}</a>”
                    </p>
                    @if ($activity['type'] === 'commented')
                        <p class="mt-1 text-sm text-stone-600">{{ Str::limit($activity['comment']->content, 110) }}</p>
                    @endif
                    <time class="mt-1 block text-xs text-stone-500" datetime="{{ $activity['occurred_at']->toIso8601String() }}">{{ $activity['occurred_at']->diffForHumans() }}</time>
                </article>
            @empty
                <div class="py-12"><p class="font-serif text-xl font-bold">No recent activity yet.</p><p class="mt-1 text-stone-600">{{ $activityIsPersonal ? 'New likes and comments on your posts will appear here.' : 'New publishing and engagement activity will appear here.' }}</p></div>
            @endforelse
        </div>
    </section>
</div>
@endsection
