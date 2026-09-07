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
        abort_unless($post->isPubliclyVisible(), 404);

        $post->load([
            'user',
            'category',
            'tags',
            'comments' => fn ($query) => $query->with('user')->oldest(),
        ])->loadCount('likedByUsers');

        $likedByCurrentUser = auth()->check()
            && $post->likedByUsers()->whereKey(auth()->id())->exists();

        return view('public.show', compact('post', 'likedByCurrentUser'));
    }
}
