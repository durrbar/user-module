<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Models\User;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the OAuth provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider, Request $request)
    {
        try {
            // Retrieve user info from the social provider
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Find or create the user based on email
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                [
                    'first_name' => Str::before($socialUser->getName(), ' ') ?? '',
                    'last_name' => Str::after($socialUser->getName(), ' ') ?? '',
                    'avatar' => $socialUser->getAvatar(),
                    'email_verified_at' => now(),
                ]
            );

            // Check if the social account exists for this user and provider or create
            $user->socialAccounts()->firstOrCreate(
                [
                    'provider_name' => $provider,
                ],
                [
                    'provider_id' => $socialUser->getId(),
                    'access_token' => $socialUser->token ?? null,
                    'profile_url' => $this->generateProfileUrl($provider, $socialUser),
                ]
            );

            // Log the user in
            Auth::login($user, true);

            return redirect()->away(env('FRONTEND_URL').'/about-us'); // Redirect to the intended page
        } catch (\Exception $e) {
            // Log the error for debugging
            report($e);

            return response()->json($e->getMessage());
        }
    }

    // To generate the profile URL
    private function generateProfileUrl(string $provider, $socialUser): ?string
    {
        switch ($provider) {
            case 'facebook':
                // Facebook URL format: https://facebook.com/{user_id}
                return 'https://facebook.com/'.$socialUser->getId();

            case 'twitter':
                // Twitter URL format: https://twitter.com/{username}
                return 'https://twitter.com/'.$socialUser->getNickname();

            case 'github':
                // GitHub URL format: https://github.com/{username}
                return 'https://github.com/'.$socialUser->getNickname();

            case 'linkedin':
                // LinkedIn URL format: https://www.linkedin.com/in/{username}
                return 'https://www.linkedin.com/in/'.$socialUser->getNickname();

            case 'gitlab':
                // GitLab URL format: https://gitlab.com/{username}
                return 'https://gitlab.com/'.$socialUser->getNickname();

            case 'slack':
                // Slack does not provide a profile URL by default.
                return null;

            case 'spotify':
                // Spotify URL format: https://open.spotify.com/user/{user_id}
                return 'https://open.spotify.com/user/'.$socialUser->getId();

            case 'bitbucket':
                // Bitbucket URL format: https://bitbucket.org/{username}
                return 'https://bitbucket.org/'.$socialUser->getNickname();

            default:
                // Return null or handle other providers without a URL.
                return null;
        }
    }
}
