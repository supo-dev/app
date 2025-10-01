<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Foundation\Http\FormRequest;

final class DeletePostRequest extends FormRequest
{
    public function authorize(
        #[CurrentUser] User $user,
        #[RouteParameter('post')] Post $post
    ): bool {
        return $post->user()->is($user);
    }
}
