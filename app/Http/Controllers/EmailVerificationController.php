<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\SendEmailVerification;
use App\Actions\VerifyEmail;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final readonly class EmailVerificationController
{
    public function send(
        #[CurrentUser] User $user,
        SendEmailVerification $action
    ): Response {
        $action->handle($user);

        return response(status: 200);
    }

    public function verify(
        VerifyEmailRequest $request,
        #[CurrentUser] User $user,
        VerifyEmail $action
    ): JsonResponse {
        $token = $request->string('token')->toString();

        $action->handle($user, $token);

        return response()->json([
            'message' => 'Email verified successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified' => true,
            ],
        ]);
    }
}
