<?php

use App\Models\User;
use Database\Seeders\DevelopmentAdminSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Hash;

test('development admin is seeded once as a verified super admin', function () {
    config()->set('cms.development_admin', [
        'name' => 'Blogify Admin',
        'email' => 'admin@example.com',
        'password' => 'development-password',
    ]);

    $this->seed([PermissionSeeder::class, RoleSeeder::class, DevelopmentAdminSeeder::class]);
    $this->seed(DevelopmentAdminSeeder::class);

    $admin = User::where('email', 'admin@example.com')->sole();

    expect($admin->hasVerifiedEmail())->toBeTrue()
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

test('repair command verifies only an explicitly selected development admin', function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
    $admin = User::factory()->unverified()->create(['email' => 'legacy@example.com']);
    $author = User::factory()->unverified()->create(['email' => 'author@example.com']);
    $admin->assignRole('Admin');
    $author->assignRole('Author');

    $this->artisan('blogify:verify-development-admin', ['email' => $admin->email])->assertSuccessful();
    $this->artisan('blogify:verify-development-admin', ['email' => $author->email])->assertFailed();

    expect($admin->fresh()->hasVerifiedEmail())->toBeTrue()
        ->and($author->fresh()->hasVerifiedEmail())->toBeFalse();
});
