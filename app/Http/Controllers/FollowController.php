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
    public function store(
        User $user,
        #[CurrentUser] User $loggedInUser,
        FollowUser $action
    ): Response {
        $action->handle($loggedInUser, $user);

        return response(status: 201);
    }

    public function destroy(
        User $user,
        #[CurrentUser] User $loggedInUser,
        UnfollowUser $action
    ): Response {
        $action->handle($loggedInUser, $user);

        return response(status: 204);
    }
}
