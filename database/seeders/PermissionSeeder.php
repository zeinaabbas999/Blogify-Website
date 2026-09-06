<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_posts',
            'view_all_posts',
            'create_posts',
            'edit_own_posts',
            'edit_all_posts',
            'delete_own_posts',
            'delete_all_posts',
            'publish_posts',
            'manage_users',
            'manage_roles',
            'manage_categories',
            'manage_tags',
            'manage_media',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
