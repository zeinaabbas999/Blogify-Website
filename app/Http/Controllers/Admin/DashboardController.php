<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'postsCount' => Post::count(),

            'publishedPosts' => Post::where(
                'status',
                'published'
            )->count(),

            'draftPosts' => Post::where(
                'status',
                'draft'
            )->count(),

            'usersCount' => User::count(),
        ]);
    }
}