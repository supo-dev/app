<?php

describe('register controller', function (): void {


    test('it sends back the session token', function (): void {

    })->todo();

    test('it creates the user and creates a new session (token)', function (): void {

    })->todo();

    test('it creates a user', function (): void {


        $response = $this->postJson(
            uri: route('auth.register'),
            data: [
                'nickname' => 'fbarrento',
                    'name' => 'francisco barrento',
                    'email' => 'francisco@example.com',
                    'password' => 'password'
                ]);


        expect($response->getStatusCode())
            ->toBe(201)
            ->and($response->json('nickname'))
            ->toBe('fbarrento')
            ->and($response->json('email'))
            ->toBe('francisco@example.com')
            ->and($response->json('name'))
            ->toBe('francisco barrento');


    });


});
