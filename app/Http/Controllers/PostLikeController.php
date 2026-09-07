<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->isPubliclyVisible(), 404);

        $post->likedByUsers()->syncWithoutDetaching([$request->user()->id]);

        return back();
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->isPubliclyVisible(), 404);

        $post->likedByUsers()->detach($request->user()->id);

        return back();
    }
}
