<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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
        Gate::authorize('viewAny', Post::class);

        $posts = Post::with([
            'user',
            'category',
            'tags',
        ])->when(! auth()->user()->can('view_all_posts'), fn ($query) => $query->whereBelongsTo(auth()->user()))
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
        Gate::authorize('create', Post::class);
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
        Gate::authorize('create', Post::class);
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

        if ($validated['status'] === 'published' && ! $request->user()->can('publish_posts')) {
            abort(403, 'You do not have permission to publish posts.');
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $this->uniqueSlug($validated['title']),
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
        Gate::authorize('view', $post);
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
        Gate::authorize('update', $post);
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
        Gate::authorize('update', $post);
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

        if ($validated['status'] === 'published') {
            Gate::authorize('publish', $post);
        }

        $post->update([
            'category_id' => $validated['category_id'] ?? null,
            'title' => $validated['title'],
            'slug' => $post->slug,
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
        Gate::authorize('delete', $post);
        $post->tags()->detach();

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'post';
        $slug = $base;
        $suffix = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}
