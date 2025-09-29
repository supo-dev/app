<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DataObjects\CreateUserData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nickname' => 'required|string|min:3|max:255|unique:users,nickname',
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function toDto(): CreateUserData
    {
        /** @var array{nickname: string, name: string, email: string, password: string} $validated */
        $validated = $this->validated();

        return new CreateUserData(
            nickname: $validated['nickname'],
            name: $validated['name'],
            email: $validated['email'],
            password: $validated['password'],
        );
    }
}
