<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Modules\User\Models\User;
use Throwable;

class SocialiteController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return $this->resolveProvider($provider)
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(string $provider, Request $request): RedirectResponse|JsonResponse
    {
        try {
            $socialUser = $this->resolveProvider($provider)->stateless()->user();
            $email = $socialUser->getEmail();

            if (! is_string($email) || $email === '') {
                return response()->json(['message' => 'Social provider did not return an email address.'], 422);
            }

            $fullName = $socialUser->getName();
            $safeName = is_string($fullName) ? $fullName : '';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name' => Str::before($safeName, ' '),
                    'last_name' => Str::after($safeName, ' '),
                    'avatar' => $socialUser->getAvatar(),
                    'email_verified_at' => now(),
                ]
            );

            $user->socialAccounts()->firstOrCreate(
                [
                    'provider_name' => $provider,
                ],
                [
                    'provider_id' => (string) $socialUser->getId(),
                    'access_token' => $socialUser->token ?? null,
                    'profile_url' => $this->generateProfileUrl($provider, $socialUser),
                ]
            );

            Auth::login($user, true);

            $frontendUrl = config('app.frontend_url');
            $appUrl = config('app.url');

            if (! is_string($frontendUrl) || $frontendUrl === '') {
                $frontendUrl = is_string($appUrl) ? $appUrl : '';
            }

            return redirect()->away($frontendUrl.'/about-us');
        } catch (Throwable $exception) {
            report($exception);

            return response()->json(['message' => $exception->getMessage()], 500);
        }
    }

    private function generateProfileUrl(string $provider, SocialiteUser $socialUser): ?string
    {
        $id = (string) $socialUser->getId();
        $nickname = $socialUser->getNickname();

        switch ($provider) {
            case 'facebook':
                return $id !== '' ? 'https://facebook.com/'.$id : null;

            case 'twitter':
                return is_string($nickname) && $nickname !== '' ? 'https://twitter.com/'.$nickname : null;

            case 'github':
                return is_string($nickname) && $nickname !== '' ? 'https://github.com/'.$nickname : null;

            case 'linkedin':
                return is_string($nickname) && $nickname !== '' ? 'https://www.linkedin.com/in/'.$nickname : null;

            case 'gitlab':
                return is_string($nickname) && $nickname !== '' ? 'https://gitlab.com/'.$nickname : null;

            case 'slack':
                return null;

            case 'spotify':
                return $id !== '' ? 'https://open.spotify.com/user/'.$id : null;

            case 'bitbucket':
                return is_string($nickname) && $nickname !== '' ? 'https://bitbucket.org/'.$nickname : null;

            default:
                return null;
        }
    }

    private function resolveProvider(string $provider): AbstractProvider
    {
        $socialiteProvider = Socialite::driver($provider);

        if (! $socialiteProvider instanceof AbstractProvider) {
            throw new \InvalidArgumentException("Unsupported provider [$provider].");
        }

        return $socialiteProvider;
    }
}
