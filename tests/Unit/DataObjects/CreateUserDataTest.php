<?php

declare(strict_types=1);

use App\DataObjects\CreateUserData;
use Illuminate\Support\Facades\Hash;

describe('create user data', function (): void {

    test('to array', function (): void {

        $createUserData = new CreateUserData(
            nickname: 'fbarrento',
            name: 'francisco barrento',
            email: 'francisco@example.com',
            password: 'password',
        );

        $createUserDataArray = $createUserData->toArray();
        expect($createUserDataArray)
            ->toContain('fbarrento', 'francisco barrento', 'francisco@example.com')
            ->and(Hash::check('password', $createUserDataArray['password']))
            ->toBeTrue();

    });

});
