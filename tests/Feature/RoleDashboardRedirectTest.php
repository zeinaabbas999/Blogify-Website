<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;

beforeEach(function () {
    $this->seed([PermissionSeeder::class, RoleSeeder::class]);
});

it('redirects each role to its dashboard and protects the admin dashboard', function (string $role, string $route) {
    $user = User::factory()->unverified()->create();
    $user->assignRole($role);

    $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route($route));

    if (! in_array($role, ['Super Admin', 'Admin'], true)) {
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }
})->with([
    ['Super Admin', 'admin.dashboard'],
    ['Admin', 'admin.dashboard'],
    ['Editor', 'editor.dashboard'],
    ['Author', 'author.dashboard'],
]);
