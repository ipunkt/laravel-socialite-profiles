<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Http\Controllers;

use Ipunkt\Laravel\SocialiteProfiles\Auth\AuthenticateUserWithSocialite;
use Ipunkt\Laravel\SocialiteProfiles\Auth\SocialAuthenticatedUserListener;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SocialLoginController extends Controller implements SocialAuthenticatedUserListener
{
    /**
     * authenticate user with socialite
     *
     * @param AuthenticateUserWithSocialite $authenticateUser
     * @param Request $request
     * @param string $provider
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authenticateWith(AuthenticateUserWithSocialite $authenticateUser, Request $request, $provider)
    {
        $this->checkProviderExistence($provider);

        $hasCode = $request->has('code') || $request->has('oauth_token');

        //  update redirect url
        config([
            'services.' . $provider . '.redirect' => app('url')->forceSchema(
                config('socialite-profiles.force_schema')
            ) . route('social.login', ['provider' => $provider])
        ]);

        return $authenticateUser
            ->setSocialProvider($provider)
            ->authenticate($hasCode, $this);
    }

    /**
     * user has logged in
     *
     * @param \App\User $user
     *
     * @return bool
     */
    public function userHasLoggedIn($user)
    {
        return redirect()->intended(config('socialite-profiles.redirect-when-user-logged-in'));
    }

    /**
     * checks provider existence
     *
     * @param string $provider
     */
    private function checkProviderExistence(string $provider)
    {
        if (config('services.' . $provider) === null) {
            abort(404);
        }
    }
}