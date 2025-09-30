<?php

declare(strict_types=1);

use App\Actions\LogoutUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('may logout a user', function (): void {
    $user = User::factory()->create();
    Auth::login($user);
    expect(Auth::check())->toBeTrue();

    $action = app(LogoutUser::class);
    $action->handle();

    expect(Auth::check())->toBeFalse();
});
