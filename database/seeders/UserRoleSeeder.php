<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if ($user) {
            $user->syncRoles(['Super Admin']);
        }
    }
}