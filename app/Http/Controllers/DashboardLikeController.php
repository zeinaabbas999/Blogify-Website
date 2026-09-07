<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use Illuminate\View\View;

class DashboardLikeController extends Controller
{
    public function __invoke(): View
    {
        $posts = request()->user()->likedPosts()
            ->with('user')
            ->where('status', PostStatus::PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByPivot('created_at', 'desc')
            ->paginate(15);

        return view('admin.likes.index', compact('posts'));
    }
}
