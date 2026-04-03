<?php

declare(strict_types=1);

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileAvatarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'The avatar file is required.',
            'photo.file' => 'The avatar must be a valid file.',
            'photo.mimes' => 'The avatar must be a JPG, JPEG, or PNG file.',
            'photo.max' => 'The avatar must not exceed 1MB in size.',
        ];
    }
}
