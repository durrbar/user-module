<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Models\SocialAccount;
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
        return Socialite::driver($provider)->stateless()->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function callback($provider, Request $request)
    {
        try {
            // Retrieve user info from the social provider
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Check if a user already exists with the same email
            $user = User::where('email', $socialUser->getEmail())->first();

            if (!$user) {
                // Get full name
                $fullName = $socialUser->getName();

                // Split the full name into first and last names
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                // Create a new user if it doesn't exist
                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $socialUser->getEmail(),
                    'avatar' => $socialUser->getAvatar(), // Optional: store avatar in the users table
                ]);
            }

            // Check if the social account exists for this user and provider
            $socialAccount = SocialAccount::where('user_id', $user->id)
                ->where('provider_name', $provider)
                ->first();

            if (!$socialAccount) {
                // Create the social account if it doesn't exist
                $user->socialAccounts()->create([
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'access_token' => $socialUser->token ?? null,
                    'profile_url' => $this->generateProfileUrl($provider, $socialUser),
                ]);
            }

            // Log the user in
            Auth::login($user, true);

            return $request->wantsJson() ? redirect()->away(env('FRONTEND_URL')) : redirect()->intended('/'); // Redirect to the intended page
        } catch (\Exception $e) {
            // Log the error for debugging
            report($e);

            return redirect()->route('login')->withErrors(['error' => 'Something went wrong! Please try again.']);
        }
    }

    //To generate the profile URL
    private function generateProfileUrl(string $provider, $socialUser): ?string
    {
        switch ($provider) {
            case 'facebook':
                // Facebook URL format: https://facebook.com/{user_id}
                return 'https://facebook.com/' . $socialUser->getId();

            case 'twitter':
                // Twitter URL format: https://twitter.com/{username}
                return 'https://twitter.com/' . $socialUser->getNickname();

            case 'github':
                // GitHub URL format: https://github.com/{username}
                return 'https://github.com/' . $socialUser->getNickname();

            case 'linkedin':
                // LinkedIn URL format: https://www.linkedin.com/in/{username}
                return 'https://www.linkedin.com/in/' . $socialUser->getNickname();

            case 'gitlab':
                // GitLab URL format: https://gitlab.com/{username}
                return 'https://gitlab.com/' . $socialUser->getNickname();

            case 'slack':
                // Slack does not provide a profile URL by default.
                return null;

            case 'spotify':
                // Spotify URL format: https://open.spotify.com/user/{user_id}
                return 'https://open.spotify.com/user/' . $socialUser->getId();

            case 'bitbucket':
                // Bitbucket URL format: https://bitbucket.org/{username}
                return 'https://bitbucket.org/' . $socialUser->getNickname();

            default:
                // Return null or handle other providers without a URL.
                return null;
        }
    }
}
