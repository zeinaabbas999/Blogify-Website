<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('blogify:verify-development-admin {email}', function (string $email) {
    if (app()->isProduction()) {
        $this->error('This development repair command is disabled in production.');

        return 1;
    }

    $user = User::where('email', $email)->first();

    if (! $user || ! $user->hasAnyRole(['Super Admin', 'Admin'])) {
        $this->error('No Admin or Super Admin exists with that exact email address.');

        return 1;
    }

    $user->forceFill(['email_verified_at' => now()])->save();
    $this->info("{$user->name} is now verified.");

    return 0;
})->purpose('Mark one explicitly selected development administrator as verified');
