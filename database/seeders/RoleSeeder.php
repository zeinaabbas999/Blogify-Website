<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $author = Role::firstOrCreate(['name' => 'Author']);

        $superAdmin->syncPermissions([
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'manage_users',
            'manage_categories',
            'manage_tags',
            'manage_media',
        ]);

        $admin->syncPermissions([
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'manage_users',
            'manage_categories',
            'manage_tags',
            'manage_media',
        ]);

        $editor->syncPermissions([
            'create_posts',
            'edit_posts',
            'publish_posts',
            'manage_categories',
            'manage_tags',
            'manage_media',
        ]);

        $author->syncPermissions([
            'create_posts',
            'edit_posts',
        ]);
    }
}