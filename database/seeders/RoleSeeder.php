<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $author = Role::firstOrCreate(['name' => 'Author']);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'create_posts',
            'view_posts',
            'view_all_posts',
            'edit_all_posts',
            'delete_all_posts',
            'publish_posts',
            'manage_users',
            'manage_categories',
            'manage_tags',
            'manage_media',
            'manage_settings',
        ]);

        $editor->syncPermissions([
            'create_posts',
            'view_posts',
            'view_all_posts',
            'edit_all_posts',
            'publish_posts',
            'manage_categories',
            'manage_tags',
            'manage_media',
        ]);

        $author->syncPermissions([
            'create_posts',
            'view_posts',
            'edit_own_posts',
            'delete_own_posts',
        ]);
    }
}
