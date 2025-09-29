<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class DeletePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');
        $user = $this->user();

        assert($post instanceof Post);
        assert($user instanceof User);

        return $user->id === $post->user_id;
    }
}
