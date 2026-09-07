<?php

use App\Enums\PostStatus;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
});

function dashboardPostFor(User $author, string $title, string $slug, PostStatus $status = PostStatus::PUBLISHED): Post
{
    return Post::create([
        'user_id' => $author->id,
        'title' => $title,
        'slug' => $slug,
        'content' => 'Dashboard activity content.',
        'status' => $status,
        'published_at' => $status === PostStatus::PUBLISHED ? now() : null,
    ]);
}

test('author dashboard shows only personal recent activity and metrics', function () {
    $author = User::factory()->create(['name' => 'Zeina Author']);
    $author->assignRole('Author');
    $other = User::factory()->create(['name' => 'Sarah Reader']);
    $ownPost = dashboardPostFor($author, 'My own dashboard post', 'my-own-dashboard-post');
    $otherPost = dashboardPostFor($other, 'Someone else dashboard post', 'someone-else-dashboard-post');

    Comment::create(['user_id' => $author->id, 'post_id' => $otherPost->id, 'content' => 'Unrelated personal action']);
    Comment::create(['user_id' => $other->id, 'post_id' => $ownPost->id, 'content' => 'Relevant reader response']);
    $ownPost->likedByUsers()->attach($other->id);
    $otherPost->likedByUsers()->attach($other->id);

    $response = $this->actingAs($author)->get(route('author.dashboard'));

    $response->assertOk()
        ->assertSee('My own dashboard post')
        ->assertSee('Sarah Reader')
        ->assertSee('commented on')
        ->assertSee('liked')
        ->assertSee('Relevant reader response')
        ->assertSee('You')
        ->assertDontSee('Unrelated personal action')
        ->assertDontSee('Someone else dashboard post')
        ->assertSee('My comments')
        ->assertSee('Recent Activity')
        ->assertDontSee('Recent content')
        ->assertDontSee('Registered users')
        ->assertDontSee('Posts you liked');

    expect($response->viewData('recentActivity'))->toHaveCount(3)
        ->and($response->original->getData())->not->toHaveKeys(['recentComments', 'likedPosts']);
});

test('editor sees activity across content they are authorized to manage', function () {
    $editor = User::factory()->create();
    $editor->assignRole('Editor');
    $author = User::factory()->create();
    $firstPost = dashboardPostFor($author, 'First editorial post', 'first-editorial-post');
    $secondPost = dashboardPostFor($author, 'Second editorial post', 'second-editorial-post');

    Comment::create(['user_id' => $editor->id, 'post_id' => $firstPost->id, 'content' => 'Editor personal comment']);
    Comment::create(['user_id' => $author->id, 'post_id' => $secondPost->id, 'content' => 'Author comment hidden from editor activity']);
    $firstPost->likedByUsers()->attach($editor->id);
    $secondPost->likedByUsers()->attach($author->id);

    $this->actingAs($editor)->get(route('editor.dashboard'))
        ->assertOk()
        ->assertSee('First editorial post')
        ->assertSee('Second editorial post')
        ->assertSee('Editor personal comment')
        ->assertSee('Author comment hidden from editor activity')
        ->assertSee($author->name)
        ->assertSee('liked')
        ->assertSee('My comments')
        ->assertDontSee('Registered users');
});

test('admin dashboard shows global recent comments and registered users', function () {
    $admin = User::factory()->create(['name' => 'Site Admin']);
    $admin->assignRole('Admin');
    $author = User::factory()->create(['name' => 'Mariam Writer']);
    $reader = User::factory()->create(['name' => 'Ahmed Reader']);
    $post = dashboardPostFor($author, 'Moderated post', 'moderated-post');
    Comment::create(['user_id' => $author->id, 'post_id' => $post->id, 'content' => 'Globally visible moderation comment']);
    $post->likedByUsers()->attach($reader->id);

    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Registered users')
        ->assertSee('Globally visible moderation comment')
        ->assertSee('Mariam Writer')
        ->assertSee('commented on')
        ->assertSee('Ahmed Reader')
        ->assertSee('liked')
        ->assertSee('Recent Activity')
        ->assertDontSee('Remove');
});

test('deleted comments disappear from public and dashboard views', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');
    $post = dashboardPostFor($author, 'Comment cleanup post', 'comment-cleanup-post');
    $comment = Comment::create(['user_id' => $author->id, 'post_id' => $post->id, 'content' => 'Remove this everywhere']);

    $this->actingAs($author)->delete(route('comments.destroy', $comment))->assertRedirect();

    $this->get(route('posts.show', $post))->assertDontSee('Remove this everywhere');
    $this->actingAs($author)->get(route('author.dashboard'))->assertDontSee('Remove this everywhere');
});

test('comments page is personal for normal users and global for admins', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');
    $other = User::factory()->create();
    $admin = User::factory()->create();
    $admin->assignRole('Admin');
    $post = dashboardPostFor($other, 'Comments destination', 'comments-destination');
    Comment::create(['user_id' => $author->id, 'post_id' => $post->id, 'content' => 'Authors page comment']);
    Comment::create(['user_id' => $other->id, 'post_id' => $post->id, 'content' => 'Other users comment']);

    $this->actingAs($author)->get(route('dashboard.comments'))
        ->assertOk()
        ->assertSee('Your comments')
        ->assertSee('Authors page comment')
        ->assertDontSee('Other users comment')
        ->assertSee('nav-item-active', escape: false);

    $this->actingAs($admin)->get(route('dashboard.comments'))
        ->assertOk()
        ->assertSee('All comments')
        ->assertSee('Authors page comment')
        ->assertSee('Other users comment')
        ->assertSee('Delete');
});

test('likes page contains only the current users published liked posts', function () {
    $user = User::factory()->create();
    $user->assignRole('Author');
    $other = User::factory()->create();
    $liked = dashboardPostFor($other, 'Personally liked post', 'personally-liked-post');
    $notLiked = dashboardPostFor($other, 'Not personally liked post', 'not-personally-liked-post');
    $draft = dashboardPostFor($other, 'Private liked draft', 'private-liked-draft', PostStatus::DRAFT);
    $liked->likedByUsers()->attach($user->id);
    $notLiked->likedByUsers()->attach($other->id);
    $draft->likedByUsers()->attach($user->id);

    $this->actingAs($user)->get(route('dashboard.likes'))
        ->assertOk()
        ->assertSee('Posts you liked')
        ->assertSee('Personally liked post')
        ->assertDontSee('Not personally liked post')
        ->assertDontSee('Private liked draft')
        ->assertSee('nav-item-active', escape: false);
});

test('recent activity is limited to six records', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');

    foreach (range(1, 8) as $number) {
        dashboardPostFor($author, "Activity post {$number}", "activity-post-{$number}");
    }

    $response = $this->actingAs($author)->get(route('author.dashboard'))->assertOk();

    expect($response->viewData('recentActivity'))->toHaveCount(6);
});

test('recent activity is merged newest first across engagement types', function () {
    $author = User::factory()->create(['name' => 'Feed Author']);
    $author->assignRole('Author');
    $reader = User::factory()->create(['name' => 'Feed Reader']);
    $post = dashboardPostFor($author, 'Chronological story', 'chronological-story');
    $post->forceFill(['created_at' => now()->subHours(3), 'updated_at' => now()->subHours(3), 'published_at' => now()->subHours(3)])->save();
    $comment = Comment::create(['user_id' => $reader->id, 'post_id' => $post->id, 'content' => 'Older comment']);
    $comment->forceFill(['created_at' => now()->subHours(2), 'updated_at' => now()->subHours(2)])->save();
    $post->likedByUsers()->attach($reader->id, ['created_at' => now()->subHour(), 'updated_at' => now()->subHour()]);

    $activity = $this->actingAs($author)->get(route('author.dashboard'))->assertOk()->viewData('recentActivity');

    expect($activity->pluck('type')->take(2)->all())->toBe(['liked', 'commented']);
});

test('draft activity never links to the public website', function () {
    $author = User::factory()->create();
    $author->assignRole('Author');
    $draft = dashboardPostFor($author, 'Private activity draft', 'private-activity-draft', PostStatus::DRAFT);

    $this->actingAs($author)->get(route('author.dashboard'))
        ->assertOk()
        ->assertSee(route('admin.posts.show', $draft), escape: false)
        ->assertDontSee(route('posts.show', $draft), escape: false);
});

test('engagement pages require authentication and sidebar remains role aware', function () {
    $this->get(route('dashboard.comments'))->assertRedirect(route('login'));
    $this->get(route('dashboard.likes'))->assertRedirect(route('login'));

    $author = User::factory()->create();
    $author->assignRole('Author');

    $this->actingAs($author)->get(route('dashboard.comments'))
        ->assertSee('Comments')
        ->assertSee('Likes')
        ->assertDontSee('Users')
        ->assertDontSee('Settings');
});

test('engagement pages do not depend on email verification', function () {
    $author = User::factory()->unverified()->create();
    $author->assignRole('Author');

    $this->actingAs($author)->get(route('dashboard.comments'))->assertOk();
    $this->actingAs($author)->get(route('dashboard.likes'))->assertOk();

    expect(Route::has('verification.notice'))->toBeFalse()
        ->and(Route::getRoutes()->getByName('dashboard.comments')->middleware())->not->toContain('verified')
        ->and(Route::getRoutes()->getByName('dashboard.likes')->middleware())->not->toContain('verified');
});
