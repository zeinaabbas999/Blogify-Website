<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Enums\PostStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Display all posts
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $posts = Post::with([
            'user',
            'category',
            'tags',
        ])
        ->latest()
        ->get();

        return view('admin.posts.index', [
            'posts' => $posts,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show create form
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.posts.create', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Store new post
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => PostStatus::from($validated['status']),
            'published_at' => $validated['status'] === 'published'
                ? now()
                : null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Display one post
    |--------------------------------------------------------------------------
    */

    public function show(Post $post)
    {
        $post->load([
            'user',
            'category',
            'tags',
        ]);

        return view('admin.posts.show', [
            'post' => $post,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Show edit form
    |--------------------------------------------------------------------------
    */

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        $post->load('tags');

        return view('admin.posts.edit', [
            'post' => $post,
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Update post
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $post->update([
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'],
            'featured_image' => $validated['featured_image'] ?? null,
            'status' => PostStatus::from($validated['status']),
            'published_at' => $validated['status'] === 'published'
                ? ($post->published_at ?? now())
                : null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete post
    |--------------------------------------------------------------------------
    */

    public function destroy(Post $post)
    {
        $post->tags()->detach();

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}