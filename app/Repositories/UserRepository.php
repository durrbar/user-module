<?php

declare(strict_types=1);

namespace Modules\User\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Modules\Address\Models\Address;
use Modules\Core\Exceptions\DurrbarException;
use Modules\Core\Repositories\BaseRepository;
use Modules\Role\Enums\Permission as UserPermission;
use Modules\Settings\Models\Settings;
use Modules\User\Mail\ForgetPassword;
use Modules\User\Models\Profile;
use Modules\User\Models\User;
use Modules\User\Traits\UsersTrait;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Throwable;

class UserRepository extends BaseRepository
{
    use UsersTrait;

    /**
     * @var array<string, string>
     */
    protected $fieldSearchable = [
        'name' => 'like',
        'email' => 'like',
    ];

    /**
     * @var list<string>
     */
    protected $dataArray = [
        'first_name',
        'last_name',
        'email',
        'shop_id',
    ];

    /**
     * Configure the Model
     *
     * @return class-string<User>
     **/
    public function model(): string
    {
        return User::class;
    }

    public function boot(): void
    {
        try {
            $this->pushCriteria(app(RequestCriteria::class));
        } catch (RepositoryException $e) {
        }
    }

    /**
     * @param  Request|array<string, mixed>  $request
     */
    public function storeUser(Request|array $request): User
    {
        $payload = $request instanceof Request ? $request->all() : $request;
        $fullName = $this->asString(Arr::get($payload, 'name', ''));
        $firstName = $this->asString(Arr::get($payload, 'first_name', mb_trim((string) str($fullName)->before(' '))));
        $lastName = $this->asString(Arr::get($payload, 'last_name', mb_trim((string) str($fullName)->after(' '))));

        try {
            $user = $this->create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $this->asString(Arr::get($payload, 'email', '')),
                'password' => Hash::make($this->asString(Arr::get($payload, 'password', ''))),
            ]);
            if (! $user instanceof User) {
                throw new DurrbarException(SOMETHING_WENT_WRONG);
            }
            $user->givePermissionTo(UserPermission::Customer->value);
            $addresses = Arr::get($payload, 'address', []);
            if (is_array($addresses) && $addresses !== []) {
                /** @var list<array<string, mixed>> $addressPayload */
                $addressPayload = array_values(array_filter($addresses, static fn (mixed $value): bool => is_array($value)));
                $user->address()->createMany($addressPayload);
            }
            $profile = Arr::get($payload, 'profile');
            if (is_array($profile)) {
                /** @var array<string, mixed> $profile */
                $user->profile()->create($profile);
            }
            $user->setRelation('profile', $user->profile);
            $user->setRelation('address', $user->address);
            $user->setRelation('shops', $user->shops);
            $user->setRelation('managed_shop', $user->managed_shop);

            return $user;
        } catch (Throwable $e) {
            throw new DurrbarException(SOMETHING_WENT_WRONG);
        }
    }

    /**
     * @param  Request|array<string, mixed>  $request
     */
    public function updateUser(Request|array $request, User $user): User
    {
        $payload = $request instanceof Request ? $request->all() : $request;

        try {
            $addresses = Arr::get($payload, 'address', []);
            if (is_array($addresses) && $addresses !== []) {
                foreach ($addresses as $address) {
                    if (! is_array($address)) {
                        continue;
                    }

                    $addressId = Arr::get($address, 'id');
                    if (is_string($addressId) || is_int($addressId)) {
                        /** @var array<string, mixed> $address */
                        Address::query()->findOrFail($addressId)->update($address);
                    } else {
                        $address['customer_id'] = $user->id;
                        /** @var array<string, mixed> $address */
                        Address::query()->create($address);
                    }
                }
            }

            $profile = Arr::get($payload, 'profile');
            if (is_array($profile)) {
                $profileId = Arr::get($profile, 'id');
                if (is_string($profileId) || is_int($profileId)) {
                    /** @var array<string, mixed> $profile */
                    Profile::query()->findOrFail($profileId)->update($profile);
                } else {
                    $profile['customer_id'] = $user->id;
                    /** @var array<string, mixed> $profile */
                    Profile::query()->create($profile);
                }
            }

            /** @var array<string, mixed> $input */
            $input = $request instanceof Request ? $request->only($this->dataArray) : Arr::only($payload, $this->dataArray);
            $user->update($input);
            $user->setRelation('profile', $user->profile);
            $user->setRelation('address', $user->address);
            $user->setRelation('shops', $user->shops);
            $user->setRelation('managed_shop', $user->managed_shop);

            return $user;
        } catch (Throwable $e) {
            throw new DurrbarException(SOMETHING_WENT_WRONG);
        }
    }

    public function sendResetEmail(string $email, string $token): bool
    {
        try {
            Mail::to($email)->send(new ForgetPassword($token));

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Update user email and send verification link to the user.
     *
     * @return array{message: string, status: string}
     */
    public function updateEmail(Request $request): array
    {
        $user = $request->user();
        if (! $user instanceof User) {
            throw new DurrbarException(NOT_AUTHORIZED);
        }

        $user->email = $request->string('email')->toString();
        $user->email_verified_at = null;
        $user->save();
        $user->sendEmailVerificationNotification();

        return ['message' => EMAIL_UPDATED_SUCCESSFULLY, 'status' => 'success'];
    }

    public function checkIfApplicationIsValid(): bool
    {
        $settings = Settings::getData();
        $options = is_object($settings) && property_exists($settings, 'options') && is_array($settings->options)
            ? $settings->options
            : [];
        $appSettings = $options['app_settings'] ?? [];
        $useMustVerifyLicense = is_array($appSettings) && isset($appSettings['trust']) ? (bool) $appSettings['trust'] : false;

        return $useMustVerifyLicense;
    }

    private function asString(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }
}
