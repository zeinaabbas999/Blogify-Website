<?php

namespace App\Http\Controllers;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\View\View;

class PublicPostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with(['user', 'category'])
            ->where('status', PostStatus::PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(9);

        return view('public.home', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->isPublished() && $post->published_at?->isPast(), 404);

        $post->load(['user', 'category', 'tags']);

        return view('public.show', compact('post'));
    }
}
