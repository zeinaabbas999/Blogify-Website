<?php

use App\Enums\PostStatus;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
});

function publishedPostFor(User $author, string $slug = 'published-post'): Post
{
    return Post::create([
        'user_id' => $author->id,
        'title' => 'Published post',
        'slug' => $slug,
        'content' => 'Public content.',
        'status' => PostStatus::PUBLISHED,
        'published_at' => now(),
    ]);
}

test('authenticated user can comment on a published post', function () {
    $author = User::factory()->create();
    $commenter = User::factory()->create();
    $post = publishedPostFor($author);

    $this->actingAs($commenter)->post(route('comments.store', $post), [
        'content' => 'A thoughtful response.',
    ])->assertRedirect();

    $this->assertDatabaseHas('comments', [
        'post_id' => $post->id,
        'user_id' => $commenter->id,
        'content' => 'A thoughtful response.',
    ]);

    $this->get(route('posts.show', $post))
        ->assertOk()
        ->assertSee($commenter->name)
        ->assertSee('A thoughtful response.');
});

test('comments are validated escaped and limited to published posts', function () {
    $author = User::factory()->create();
    $commenter = User::factory()->create();
    $published = publishedPostFor($author);
    $draft = Post::create(['user_id' => $author->id, 'title' => 'Draft', 'slug' => 'draft', 'content' => 'Private.', 'status' => PostStatus::DRAFT]);

    $this->actingAs($commenter)->post(route('comments.store', $published), ['content' => ''])
        ->assertSessionHasErrors('content');
    $this->actingAs($commenter)->post(route('comments.store', $published), ['content' => str_repeat('a', 2001)])
        ->assertSessionHasErrors('content');
    $this->actingAs($commenter)->post(route('comments.store', $draft), ['content' => 'Not allowed'])
        ->assertNotFound();

    Comment::create(['user_id' => $commenter->id, 'post_id' => $published->id, 'content' => '<script>alert("xss")</script>']);
    $this->get(route('posts.show', $published))
        ->assertSee('<script>alert("xss")</script>')
        ->assertDontSee('<script>alert("xss")</script>', escape: false);
});

test('comment deletion is limited to its owner or super admin', function () {
    $author = User::factory()->create();
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('Super Admin');
    $post = publishedPostFor($author);
    $comment = Comment::create(['user_id' => $owner->id, 'post_id' => $post->id, 'content' => 'Owned comment']);

    $this->actingAs($other)->delete(route('comments.destroy', $comment))->assertForbidden();
    expect($comment->fresh())->not->toBeNull();

    $this->actingAs($owner)->delete(route('comments.destroy', $comment))->assertRedirect();
    expect($comment->fresh())->toBeNull();

    $adminComment = Comment::create(['user_id' => $other->id, 'post_id' => $post->id, 'content' => 'Admin removable']);
    $this->actingAs($superAdmin)->delete(route('comments.destroy', $adminComment))->assertRedirect();
    expect($adminComment->fresh())->toBeNull();
});

test('likes are authenticated published-only unique and reversible', function () {
    $author = User::factory()->create();
    $reader = User::factory()->create();
    $published = publishedPostFor($author);
    $draft = Post::create(['user_id' => $author->id, 'title' => 'Draft', 'slug' => 'like-draft', 'content' => 'Private.', 'status' => PostStatus::DRAFT]);

    $this->post(route('posts.likes.store', $published))->assertRedirect(route('login'));
    $this->actingAs($reader)->post(route('posts.likes.store', $draft))->assertNotFound();

    $this->actingAs($reader)->post(route('posts.likes.store', $published))->assertRedirect();
    $this->actingAs($reader)->post(route('posts.likes.store', $published))->assertRedirect();
    $this->assertDatabaseCount('post_likes', 1);
    $this->assertDatabaseHas('post_likes', ['post_id' => $published->id, 'user_id' => $reader->id]);

    $this->actingAs($reader)->delete(route('posts.likes.destroy', $published))->assertRedirect();
    $this->assertDatabaseCount('post_likes', 0);
});

test('deleting a post cascades its comments and likes', function () {
    $author = User::factory()->create();
    $reader = User::factory()->create();
    $post = publishedPostFor($author);
    Comment::create(['user_id' => $reader->id, 'post_id' => $post->id, 'content' => 'Cascade me']);
    $post->likedByUsers()->attach($reader->id);

    $post->delete();

    $this->assertDatabaseCount('comments', 0);
    $this->assertDatabaseCount('post_likes', 0);
});
