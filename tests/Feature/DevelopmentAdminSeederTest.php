<?php

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Database\Seeders\DevelopmentAdminSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

test('development admin is seeded once as a super admin', function () {
    config()->set('cms.development_admin', [
        'name' => 'Blogify Admin',
        'email' => 'admin@example.com',
        'password' => 'development-password',
    ]);

    $this->seed([PermissionSeeder::class, RoleSeeder::class, DevelopmentAdminSeeder::class]);
    $this->seed(DevelopmentAdminSeeder::class);

    $admin = User::where('email', 'admin@example.com')->sole();

    expect($admin->email_verified_at)->not->toBeNull()
        ->and($admin->hasRole('Super Admin'))->toBeTrue()
        ->and(User::where('email', 'admin@example.com')->count())->toBe(1);

    expect(Hash::check('development-password', $admin->password))->toBeTrue();

    $this->post(route('login'), [
        'email' => 'admin@example.com',
        'password' => 'development-password',
    ])->assertRedirect(route('dashboard'));

    $this->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
    $this->get(route('admin.dashboard'))->assertOk();
});

test('seeder promotes an existing author without replacing identity or content', function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    config()->set('cms.development_admin', [
        'name' => 'Configured Name',
        'email' => 'zeina.abbas999@gmail.com',
        'password' => 'development-password',
    ]);

    $author = User::factory()->create([
        'name' => 'Existing Profile Name',
        'email' => 'zeina.abbas999@gmail.com',
    ]);
    $author->assignRole('Author');
    $post = Post::create([
        'user_id' => $author->id,
        'title' => 'Existing article',
        'slug' => 'existing-article',
        'content' => 'Preserved content.',
        'status' => PostStatus::DRAFT,
    ]);

    $this->seed(DevelopmentAdminSeeder::class);
    $promoted = User::where('email', 'zeina.abbas999@gmail.com')->sole();

    expect($promoted->id)->toBe($author->id)
        ->and($promoted->name)->toBe('Existing Profile Name')
        ->and($promoted->hasRole('Super Admin'))->toBeTrue()
        ->and($promoted->hasRole('Author'))->toBeFalse()
        ->and($post->fresh()->user_id)->toBe($author->id);
});
