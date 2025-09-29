<?php

declare(strict_types=1);

namespace App\Actions;

use App\DataObjects\CreateUserData;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateUserAction
{
    /**
     * @throws Throwable
     */
    public function handle(CreateUserData $createUserData): User
    {

        return DB::transaction(function () use ($createUserData): User {
            return User::query()
                ->create($createUserData->toArray());
        });

    }
}
