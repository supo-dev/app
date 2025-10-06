<?php

declare(strict_types=1);

use App\Enums\Support\Keyboard;
use App\Support\KeyHandler;

it('may register and handle key', function (): void {
    $handler = new KeyHandler();
    $executed = false;

    $handler->on(Keyboard::F, function () use (&$executed) {
        $executed = true;

        return 'following';
    });

    $result = $handler->handle('f');

    expect($executed)->toBeTrue()
        ->and($result)->toBe('following');
});

it('returns null for unregistered key', function (): void {
    $handler = new KeyHandler();

    $result = $handler->handle('x');

    expect($result)->toBeNull();
});

it('may check if handler exists', function (): void {
    $handler = new KeyHandler();

    $handler->on(Keyboard::E, fn () => 'explore');

    expect($handler->hasHandler(Keyboard::E))->toBeTrue()
        ->and($handler->hasHandler(Keyboard::F))->toBeFalse();
});

it('may chain multiple handlers', function (): void {
    $handler = new KeyHandler();

    $handler
        ->on(Keyboard::F, fn () => 'following')
        ->on(Keyboard::E, fn () => 'explore');

    expect($handler->handle('f'))->toBe('following')
        ->and($handler->handle('e'))->toBe('explore');
});
