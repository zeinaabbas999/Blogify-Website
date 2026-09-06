<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

test('email verification screen can be rendered', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get('/verify-email');

    $response->assertStatus(200);
    $response->assertSee('fetch("\\/email\\/verification-status"', escape: false);
    $response->assertSee('window.location.assign("\\/dashboard")', escape: false);
});

test('email can be verified', function () {
    $user = User::factory()->unverified()->create();

    Event::fake();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);
    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertOk()->assertViewIs('auth.email-verified');
});

test('email is not verified with invalid hash', function () {
    $user = User::factory()->unverified()->create();

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('verification status reports only state for the authenticated user', function () {
    $user = User::factory()->unverified()->create();

    $this->getJson(route('verification.status'))->assertUnauthorized();

    $this->actingAs($user)->getJson(route('verification.status'))
        ->assertOk()
        ->assertExactJson([
            'verified' => false,
        ])
        ->assertHeader('Cache-Control', 'no-store, private');

    $user->markEmailAsVerified();

    $this->actingAs($user)->getJson(route('verification.status'))
        ->assertExactJson([
            'verified' => true,
        ]);
});

test('verification link cannot verify a different authenticated user', function () {
    $owner = User::factory()->unverified()->create();
    $other = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $owner->id, 'hash' => sha1($owner->email)]
    );

    $this->actingAs($other)->get($verificationUrl)->assertForbidden();

    expect($owner->fresh()->hasVerifiedEmail())->toBeFalse()
        ->and($other->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('logged out verification link continues through login', function () {
    $user = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $this->get($verificationUrl)
        ->assertRedirect(route('login'))
        ->assertSessionHas('url.intended', $verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});

test('already used verification link remains safe', function () {
    $user = User::factory()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)->get($verificationUrl)
        ->assertOk()
        ->assertViewIs('auth.email-verified')
        ->assertViewHas('alreadyVerified', true);
});

test('expired verification link is rejected', function () {
    $user = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->subMinute(),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $this->actingAs($user)->get($verificationUrl)->assertForbidden();
    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
