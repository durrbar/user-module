<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileAvatarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:1024'], // Ensure file type and size constraints
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization logic can be expanded here if needed
    }

    /**
     * Get custom messages for validator errors.
     */
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
