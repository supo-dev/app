<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Support\EmailVerificationTokenManager;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateUserEmailVerificationRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'size:'.EmailVerificationTokenManager::TOKEN_LENGTH],
        ];
    }
}
