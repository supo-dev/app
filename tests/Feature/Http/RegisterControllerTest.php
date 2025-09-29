<?php

declare(strict_types=1);

test('it creates a user', function (): void {

    $response = $this->postJson(
        uri: route('auth.register'),
        data: [
            'nickname' => 'fbarrento',
            'name' => 'francisco barrento',
            'email' => 'francisco@example.com',
            'password' => 'password',
        ]);

    expect($response->getStatusCode())
        ->toBe(201)
        ->and($response->json('token'))
        ->not()->toBeNull();
});
