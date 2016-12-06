<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Auth;

interface SocialAuthenticatedUserListener
{
    /**
     * user has logged in
     *
     * @param \App\User $user
     *
     * @return bool
     */
    public function userHasLoggedIn($user);
}