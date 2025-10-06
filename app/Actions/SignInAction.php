<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final readonly class SignInAction
{
    public function handle(User $user): void
    {
        Auth::login($user);
    }
}
