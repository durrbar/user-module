<?php

declare(strict_types=1);

test('new users can register', function (): void {
    $prefix = '/api/v1';
    $token = 'test-csrf-token';

    $response = $this->withSession(['_token' => $token])->post($prefix.'/register', [
        '_token' => $token,
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test.user@gmail.com',
        'password' => 'V3ry$tr0ngP@ss_2026xZ',
        'password_confirmation' => 'V3ry$tr0ngP@ss_2026xZ',
    ]);

    $response
        ->assertStatus(302)
        ->assertSessionHasNoErrors();
});
