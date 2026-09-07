<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $canViewAllPosts = $user->can('view_all_posts');
        $posts = Post::query()
            ->when(! $canViewAllPosts, fn ($query) => $query->whereBelongsTo($user));

        $recentComments = Comment::query()
            ->with(['user', 'post'])
            ->whereHas('post', fn ($query) => $query
                ->where('status', PostStatus::PUBLISHED)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->when(! $canViewAllPosts, fn ($query) => $query->whereBelongsTo($user)))
            ->latest()
            ->limit(6)
            ->get();

        $recentLikes = PostLike::query()
            ->with(['user', 'post'])
            ->whereHas('post', fn ($query) => $query
                ->where('status', PostStatus::PUBLISHED)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->when(! $canViewAllPosts, fn ($query) => $query->whereBelongsTo($user)))
            ->latest()
            ->limit(6)
            ->get();

        $recentPosts = (clone $posts)->with('user')->latest()->limit(6)->get();

        $recentActivity = $recentPosts
            ->map(fn (Post $post) => [
                'type' => $post->isPubliclyVisible() ? 'published' : 'created',
                'actor' => $post->user,
                'post' => $post,
                'occurred_at' => $post->isPubliclyVisible() ? $post->published_at : $post->created_at,
            ])
            ->concat($recentComments->map(fn (Comment $comment) => [
                'type' => 'commented',
                'actor' => $comment->user,
                'post' => $comment->post,
                'comment' => $comment,
                'occurred_at' => $comment->created_at,
            ]))
            ->concat($recentLikes->map(fn (PostLike $like) => [
                'type' => 'liked',
                'actor' => $like->user,
                'post' => $like->post,
                'occurred_at' => $like->created_at,
            ]))
            ->sortByDesc('occurred_at')
            ->take(6)
            ->values();

        $usersCount = $user->can('manage_users') ? User::count() : null;

        return view('admin.dashboard', [
            'postsCount' => (clone $posts)->count(),

            'publishedPosts' => (clone $posts)->where(
                'status',
                'published'
            )->count(),

            'draftPosts' => (clone $posts)->where(
                'status',
                'draft'
            )->count(),

            'fourthMetricLabel' => $usersCount !== null ? 'Registered users' : 'My comments',
            'fourthMetricValue' => $usersCount ?? $user->comments()->count(),
            'recentActivity' => $recentActivity,
            'activityIsPersonal' => ! $canViewAllPosts,
        ]);
    }
}
