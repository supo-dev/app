<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser] User $currentUser,
        #[RouteParameter('user')] User $user
    ): bool {
        return $currentUser->is($user);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(
        #[CurrentUser] User $user
    ): array {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ];
    }
}
