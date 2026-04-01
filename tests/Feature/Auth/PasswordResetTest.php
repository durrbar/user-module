<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\User\Models\User;

test('reset password link can be requested', function (): void {
    $prefix = '/api/v1';
    $token = 'test-csrf-token';

    $user = User::factory()->create();

    $this->withSession(['_token' => $token])->post($prefix.'/forgot-password', [
        '_token' => $token,
        'email' => $user->email,
    ])->assertSessionHas('status');
});

test('password can be reset with valid token', function (): void {
    $prefix = '/api/v1';
    $csrfToken = 'test-csrf-token';

    $user = User::factory()->create();
    $resetToken = Password::broker()->createToken($user);

    $response = $this->withSession(['_token' => $csrfToken])->post($prefix.'/reset-password', [
        '_token' => $csrfToken,
        'token' => $resetToken,
        'email' => $user->email,
        'password' => 'V3ry$tr0ngP@ss_2026xY',
        'password_confirmation' => 'V3ry$tr0ngP@ss_2026xY',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status');

    expect(Hash::check('V3ry$tr0ngP@ss_2026xY', (string) $user->fresh()?->password))->toBeTrue();
});
