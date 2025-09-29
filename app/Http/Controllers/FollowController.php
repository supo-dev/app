<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\FollowUser;
use App\Actions\UnfollowUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class FollowController
{
    public function store(Request $request, User $user, FollowUser $action): Response
    {
        $loggedInUser = $request->user();

        $action->handle($loggedInUser, $user);

        return response(status: 201);
    }

    public function destroy(Request $request, User $user, UnfollowUser $action): Response
    {
        $loggedInUser = $request->user();

        $action->handle($loggedInUser, $user);

        return response(status: 204);
    }
}
