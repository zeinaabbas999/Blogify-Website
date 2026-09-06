<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void{
        $permissions = [
            'create_posts',
            'edit_posts',
            'delete_posts',
            'publish_posts',
            'manage_users',
            'manage_categories',
            'manage_tags',
            'manage_media',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
