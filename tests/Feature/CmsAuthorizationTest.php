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

test('public website hides drafts and displays published posts', function () {
    $author = User::factory()->create();

    Post::create(['user_id' => $author->id, 'title' => 'Visible story', 'slug' => 'visible-story', 'content' => 'Published.', 'status' => PostStatus::PUBLISHED, 'published_at' => now()]);
    Post::create(['user_id' => $author->id, 'title' => 'Hidden draft', 'slug' => 'hidden-draft', 'content' => 'Draft.', 'status' => PostStatus::DRAFT]);

    $this->get(route('home'))->assertOk()->assertSee('Visible story')->assertDontSee('Hidden draft');
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
