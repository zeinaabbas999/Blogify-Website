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
