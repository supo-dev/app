<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SendEmailVerification;
use App\Actions\VerifyEmail;
use App\Http\Requests\UpdateUserEmailVerificationRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class UserEmailVerificationController
{
    public function show(
        #[CurrentUser] User $user,
        SendEmailVerification $action
    ): Response {
        $action->handle($user);

        return response(status: 200);
    }

    public function update(
        UpdateUserEmailVerificationRequest $request,
        #[CurrentUser] User $user,
        VerifyEmail $action
    ): JsonResponse {
        $token = $request->string('token')->toString();

        $action->handle($user, $token);

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'email_verified' => true,
            ],
        ]);
    }
}
