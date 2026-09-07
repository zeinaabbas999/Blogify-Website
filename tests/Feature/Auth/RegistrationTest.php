<?php

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Notification::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard'));
    expect(auth()->user()->hasRole('Author'))->toBeTrue();

    $user = User::where('email', 'test@example.com')->sole();

    expect(User::where('email', 'test@example.com')->count())->toBe(1)
        ->and($user->email_verified_at)->toBeNull()
        ->and(Notification::sent($user, VerifyEmail::class))->toHaveCount(0);
});

test('newly registered user can immediately enter the author area', function () {
    Notification::fake();

    $this->post('/register', [
        'name' => 'Immediate User',
        'email' => 'immediate@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertRedirect(route('dashboard'));

    $user = User::where('email', 'immediate@example.com')->sole();

    expect($user->email_verified_at)->toBeNull();
    $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route('author.dashboard'));
    $this->actingAs($user)->get(route('author.dashboard'))->assertOk();
    Notification::assertNothingSent();
});
