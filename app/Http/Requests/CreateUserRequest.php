<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\UserUsername;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class CreateUserRequest extends FormRequest
{
    /**
     * @return array<string, array<int, ValidationRule|string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', new UserUsername()],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
