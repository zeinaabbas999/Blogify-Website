<?php

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed(PermissionSeeder::class);
    $this->seed(RoleSeeder::class);
});

test('author cannot access user or settings administration', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');

    $this->actingAs($author)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($author)->get(route('admin.settings.index'))->assertForbidden();
});

test('author cannot access another authors post', function () {
    $author = User::factory()->create();
    $other = User::factory()->create();
    $author->assignRole('Author');
    $other->assignRole('Author');

    $post = Post::create([
        'user_id' => $other->id,
        'title' => 'Private draft',
        'slug' => 'private-draft',
        'content' => 'Not available to another author.',
        'status' => PostStatus::DRAFT,
    ]);

    $this->actingAs($author)->get(route('admin.posts.show', $post))->assertForbidden();
    $this->actingAs($author)->get(route('admin.posts.edit', $post))->assertForbidden();
    $this->actingAs($author)->delete(route('admin.posts.destroy', $post))->assertForbidden();
});

test('author can draft and publish only their own posts', function () {
    $author = User::factory()->create();
    $other = User::factory()->create();
    $author->assignRole('Author');
    $other->assignRole('Author');

    $this->actingAs($author)->post(route('admin.posts.store'), [
        'title' => 'Author published story',
        'content' => 'Published by its owner.',
        'status' => 'published',
    ])->assertRedirect(route('admin.posts.index'));

    $published = Post::where('title', 'Author published story')->sole();
    expect($published->user_id)->toBe($author->id)
        ->and($published->status)->toBe(PostStatus::PUBLISHED)
        ->and($published->published_at)->not->toBeNull();

    $otherPost = Post::create([
        'user_id' => $other->id,
        'title' => 'Another author story',
        'slug' => 'another-author-story',
        'content' => 'Not editable by the first author.',
        'status' => PostStatus::DRAFT,
    ]);

    $this->actingAs($author)->put(route('admin.posts.update', $published), [
        'title' => 'Author changed to draft',
        'content' => 'Still owned by its author.',
        'status' => 'draft',
    ])->assertRedirect(route('admin.posts.index'));

    expect($published->fresh()->status)->toBe(PostStatus::DRAFT);

    $this->actingAs($author)->put(route('admin.posts.update', $otherPost), [
        'title' => 'Forbidden change',
        'content' => 'Forbidden.',
        'status' => 'published',
    ])->assertForbidden();
});

test('author listings and dashboard contain only their posts', function () {
    $author = User::factory()->create();
    $other = User::factory()->create();
    $author->assignRole('Author');
    $other->assignRole('Author');

    Post::create(['user_id' => $author->id, 'title' => 'Authors own post', 'slug' => 'authors-own-post', 'content' => 'Own.', 'status' => PostStatus::DRAFT]);
    Post::create(['user_id' => $other->id, 'title' => 'Other private post', 'slug' => 'other-private-post', 'content' => 'Other.', 'status' => PostStatus::DRAFT]);

    $this->actingAs($author)->get(route('author.dashboard'))
        ->assertOk()
        ->assertSee('Authors own post')
        ->assertDontSee('Other private post');

    $this->actingAs($author)->get(route('admin.posts.index'))
        ->assertOk()
        ->assertSee('Authors own post')
        ->assertDontSee('Other private post');
});

test('editor sees and edits posts from every author but cannot manage users', function () {
    $editor = User::factory()->create();
    $author = User::factory()->create();
    $editor->assignRole('Editor');
    $author->assignRole('Author');

    $post = Post::create(['user_id' => $author->id, 'title' => 'Review this draft', 'slug' => 'review-this-draft', 'content' => 'Draft.', 'status' => PostStatus::DRAFT]);

    $this->actingAs($editor)->get(route('editor.dashboard'))->assertOk()->assertSee('Review this draft');
    $this->actingAs($editor)->get(route('admin.posts.index'))->assertOk()->assertSee('Review this draft');
    $this->actingAs($editor)->get(route('admin.posts.edit', $post))->assertOk();

    $this->actingAs($editor)->put(route('admin.posts.update', $post), [
        'title' => 'Reviewed and published',
        'content' => 'Reviewed.',
        'status' => 'published',
    ])->assertRedirect(route('admin.posts.index'));

    $post->refresh();
    expect($post->status)->toBe(PostStatus::PUBLISHED)
        ->and($post->published_at)->not->toBeNull();

    $this->actingAs($editor)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($editor)->delete(route('admin.users.destroy', $author))->assertForbidden();
});

test('only super admin can delete another user', function () {
    $admin = User::factory()->create();
    $superAdmin = User::factory()->create();
    $target = User::factory()->create();
    $admin->assignRole('Admin');
    $superAdmin->assignRole('Super Admin');
    $target->assignRole('Author');

    $this->actingAs($admin)->delete(route('admin.users.destroy', $target))->assertForbidden();
    expect($target->fresh())->not->toBeNull();

    $this->actingAs($superAdmin)->delete(route('admin.users.destroy', $target))
        ->assertRedirect(route('admin.users.index'));
    expect($target->fresh())->toBeNull();
});

test('public website hides drafts and displays published posts', function () {
    $author = User::factory()->create();
    $otherAuthor = User::factory()->create();

    Post::create(['user_id' => $author->id, 'title' => 'Visible story', 'slug' => 'visible-story', 'content' => 'Published.', 'status' => PostStatus::PUBLISHED, 'published_at' => now()]);
    Post::create(['user_id' => $otherAuthor->id, 'title' => 'Other visible story', 'slug' => 'other-visible-story', 'content' => 'Also published.', 'status' => PostStatus::PUBLISHED, 'published_at' => now()]);
    Post::create(['user_id' => $author->id, 'title' => 'Hidden draft', 'slug' => 'hidden-draft', 'content' => 'Draft.', 'status' => PostStatus::DRAFT]);

    $this->actingAs($author)->get(route('home'))->assertOk()->assertSee('Visible story')->assertSee('Other visible story')->assertDontSee('Hidden draft');
    $this->get('/articles/hidden-draft')->assertNotFound();
});

test('post slugs are generated uniquely and remain stable when titles change', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');

    $payload = [
        'title' => 'My First Blog Post',
        'content' => 'Readable post content.',
        'status' => 'draft',
    ];

    $this->actingAs($author)->post(route('admin.posts.store'), $payload)->assertRedirect(route('admin.posts.index'));
    $this->actingAs($author)->post(route('admin.posts.store'), $payload)->assertRedirect(route('admin.posts.index'));

    $posts = Post::whereBelongsTo($author)->oldest()->get();
    expect($posts->pluck('slug')->all())->toBe(['my-first-blog-post', 'my-first-blog-post-2']);

    $this->actingAs($author)->put(route('admin.posts.update', $posts->first()), [
        ...$payload,
        'title' => 'A Completely Different Title',
    ])->assertRedirect(route('admin.posts.index'));

    expect($posts->first()->fresh()->slug)->toBe('my-first-blog-post');
});

test('user deletion flash message is rendered once', function () {
    $admin = User::factory()->create();
    $target = User::factory()->create();
    $admin->assignRole('Super Admin');
    $target->assignRole('Author');

    $response = $this->actingAs($admin)
        ->delete(route('admin.users.destroy', $target))
        ->assertRedirect(route('admin.users.index'));

    $page = $this->actingAs($admin)->get($response->headers->get('Location'))->assertOk();

    expect(substr_count($page->getContent(), 'User deleted successfully.'))->toBe(1);
});
