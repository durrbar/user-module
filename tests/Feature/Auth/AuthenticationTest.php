<?php

use Modules\User\Models\User;

test('users can authenticate using the login screen', function (): void {
    $prefix = '/api/v1';
    $token = 'test-csrf-token';
    $user = User::factory()->create();

    $response = $this->withSession(['_token' => $token])->post($prefix.'/login', [
        '_token' => $token,
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertStatus(302);
});

test('users can not authenticate with invalid password', function (): void {
    $prefix = '/api/v1';
    $token = 'test-csrf-token';
    $user = User::factory()->create();

    $this->withSession(['_token' => $token])->post($prefix.'/login', [
        '_token' => $token,
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function (): void {
    $prefix = '/api/v1';
    $token = 'test-csrf-token';
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['_token' => $token])
        ->post($prefix.'/logout', ['_token' => $token]);

    $response->assertStatus(302);
});
