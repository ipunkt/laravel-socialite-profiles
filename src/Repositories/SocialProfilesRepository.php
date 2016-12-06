<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Repositories;

use Illuminate\Contracts\Auth\Guard;
use Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialProfilesRepository
{
    /**
     * repository model
     *
     * @var UserSocialProfile
     */
    private $model;

    /**
     * auth guard
     *
     * @var Guard
     */
    private $auth;

    /**
     * SocialProfilesRepository constructor.
     *
     * @param UserSocialProfile $model
     * @param Guard $auth
     */
    public function __construct(UserSocialProfile $model, Guard $auth)
    {
        $this->model = $model;
        $this->auth = $auth;
    }

    /**
     * finds a user entry by socialite user or creates one
     *
     * @param \Laravel\Socialite\Contracts\User $socialiteUser
     * @param string $provider
     *
     * @return \App\User
     */
    public function findBySocialiteUserOrCreate(SocialiteUser $socialiteUser, $provider)
    {
        /** @var UserSocialProfile $socialProfile */
        $socialProfile = $this->model
            ->whereProvider($provider)
            ->whereUid($socialiteUser->getId())
            ->first();

        if (null !== $socialProfile) {
            return $socialProfile->user;
        }

        $email = trim(strtolower($socialiteUser->getEmail()));

        /** @var \App\User $user */
        $user = $this->auth->check()
            ? $this->auth->user()
            : $this->getUser()->whereEmail($email)->first();

        //  no email already registered the registration way
        if (null === $user) {
            //  create user
            $user = $this->getUser()->create([
                'email' => null,
                'name' => $socialiteUser->getName(),
                'avatar' => $socialiteUser->getAvatar(),
            ]);
        }

        //  attach social profile to user
        $user->profiles()->create([
            'provider' => $provider,
            'uid' => $socialiteUser->getId(),
            'nickname' => $socialiteUser->getNickname(),
            'name' => $socialiteUser->getName(),
            'email' => $email,
            'avatar' => $socialiteUser->getAvatar(),
        ]);

        return $user;
    }

    /**
     * returns the configured user
     *
     * @return \App\User
     */
    private function getUser()
    {
        return app(config('socialite-profiles.user-model', \App\User::class));
    }
}