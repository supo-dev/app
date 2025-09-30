<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FollowUser;
use App\Actions\UnfollowUser;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\Response;

final class FollowController
{
    public function store(#[CurrentUser] User $loggedInUser, User $user, FollowUser $action): Response
    {
        $action->handle($loggedInUser, $user);

        return response(status: 201);
    }

    public function destroy(#[CurrentUser] User $loggedInUser, User $user, UnfollowUser $action): Response
    {
        $action->handle($loggedInUser, $user);

        return response(status: 204);
    }
}
