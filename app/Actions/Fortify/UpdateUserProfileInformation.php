<?php

namespace Modules\User\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Modules\User\Models\User;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'phoneNumber' => ['required', 'string', 'max:14'],
            'birthday' => ['required', 'date_format:Y-m-d'],
            'gender' => ['nullable', 'string'],
            'locale' => ['nullable', 'string'],
        ])->validateWithBag('updateProfileInformation');

        $data = [
            'email' => $input['email'],
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'phone' => $input['phoneNumber'],
            'birthday' => $input['birthday'],
            'gender' => $input['gender'] ?? null,
            'locale' => $input['locale'] ?? null,
        ];

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $data['email_verified_at'] = null;
            $user->forceFill($data)->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill($data)->save();
        }
    }
}
