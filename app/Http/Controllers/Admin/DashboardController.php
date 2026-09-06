<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $posts = Post::query()
            ->when(! auth()->user()->can('view_all_posts'), fn ($query) => $query->whereBelongsTo(auth()->user()));

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

            'usersCount' => auth()->user()->can('manage_users') ? User::count() : null,
            'recentPosts' => (clone $posts)->with('user')->latest()->limit(6)->get(),
        ]);
    }
}
