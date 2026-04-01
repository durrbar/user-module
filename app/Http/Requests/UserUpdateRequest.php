<?php

namespace Modules\User\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['email', 'unique:users'],
            'shop_id' => ['nullable', 'exists:Modules\Ecommerce\Models\Shop,id'],
            'profile' => ['array'],
            'address' => ['array'],
            // 'shop' => ['array'],
        ];
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'Name is not a valid string',
            'name.max:255' => 'Name can not be more than 255 character',
            'email.email' => 'email is not a valid email address',
            'email.unique:users' => 'email must be unique',
            'address.array' => 'address is not a valid json',
            'profile.array' => 'profile is not a valid json',
        ];
    }

    protected function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
