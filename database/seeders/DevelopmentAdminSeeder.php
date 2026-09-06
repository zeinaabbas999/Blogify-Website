<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DevelopmentAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->isProduction()) {
            $this->command?->warn('Development admin seeding is disabled in production.');

            return;
        }

        $email = config('cms.development_admin.email');

        if (! is_string($email) || $email === '') {
            $this->command?->warn('Development admin was not seeded because DEVELOPMENT_ADMIN_EMAIL is empty.');

            return;
        }

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('cms.development_admin.name'),
                'password' => Hash::make(config('cms.development_admin.password')),
                'email_verified_at' => now(),
            ],
        );

        $user->syncRoles(['Super Admin']);
    }
}
