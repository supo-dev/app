<?php

declare(strict_types=1);

use App\Actions\SignInAction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

it('may sign in a user', function (): void {
    $user = User::factory()->create();
    expect(Auth::check())->toBeFalse();

    $action = app(SignInAction::class);
    $action->handle($user);

    expect(Auth::check())->toBeTrue()
        ->and(Auth::id())->toBe($user->id);
});
