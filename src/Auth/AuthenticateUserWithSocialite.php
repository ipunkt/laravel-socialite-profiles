<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Auth;

use DonePM\SocialiteProfiles\Exceptions\SocialiteException;
use DonePM\SocialiteProfiles\Repositories\SocialProfilesRepository;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Socialite\Contracts\Factory as Socialite;

class AuthenticateUserWithSocialite
{
    /**
     * social profiles repository
     *
     * @var SocialProfilesRepository
     */
    private $socialProfilesRepository;

    /**
     * socialite
     *
     * @var Socialite
     */
    private $socialite;

    /**
     * authentication guard
     *
     * @var Guard
     */
    private $auth;

    /**
     * current social provider to use
     *
     * @var string
     */
    private $provider;

    /**
     * constructing AuthenticateUserWithSocialite
     *
     * @param SocialProfilesRepository $socialProfilesRepository
     * @param Socialite $socialite
     * @param Guard $auth
     */
    public function __construct(SocialProfilesRepository $socialProfilesRepository, Socialite $socialite, Guard $auth)
    {
        $this->socialProfilesRepository = $socialProfilesRepository;
        $this->socialite = $socialite;
        $this->auth = $auth;
    }

    /**
     * sets social provider
     *
     * @param string $provider
     *
     * @return $this
     */
    public function setSocialProvider($provider)
    {
        $this->provider = strtolower($provider);

        return $this;
    }

    /**
     * authenticates a user by socialite provider
     *
     * @param bool $hasCode
     * @param \DonePM\SocialiteProfiles\Auth\SocialAuthenticatedUserListener $listener
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function authenticate($hasCode, SocialAuthenticatedUserListener $listener)
    {
        if ( ! $hasCode) {
            return $this->getAuthorizationFirst();
        }

        $user = $this->socialProfilesRepository->findBySocialiteUserOrCreate($this->getUser(), $this->provider);

        if ( ! $this->auth->check()) {
            $this->auth->login($user, true);
        } else {
            //  @TODO fire event social provider successfully attached
        }

        return $listener->userHasLoggedIn($user);
    }

    /**
     * returns the authorization redirect
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function getAuthorizationFirst()
    {
        return $this->getSocialiteProvider()->redirect();
    }

    /**
     * returns the returned user
     *
     * @return \Laravel\Socialite\Contracts\User
     */
    private function getUser()
    {
        return $this->getSocialiteProvider()->user();
    }

    /**
     * returns the current socialite provider
     *
     * @return \Laravel\Socialite\Contracts\Provider
     * @throws SocialiteException when no provider set
     */
    private function getSocialiteProvider()
    {
        if (empty($this->provider)) {
            SocialiteException::noProviderSet();
        }

        return $this->socialite->driver($this->provider);
    }
}