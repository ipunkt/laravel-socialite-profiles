<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Ipunkt\Laravel\SocialiteProfiles\Models\HasSocialProfiles;
use Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile;

class SocialProfileController extends Controller
{
    /**
     * SocialProfileController constructor.
     * add auth middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * destroys an attached social profile from authenticated user
     *
     * @param Request $request
     * @param string $provider
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Request $request, string $provider)
    {
        /** @var \App\User|HasSocialProfiles $user */
        $user = $request->user();

        /** @var UserSocialProfile $profile */
        $profile = $user->profiles()->whereProvider($provider)->firstOrFail();

        if ($profile->delete()) {
            //  @TODO fire event for successful detaching social profile
        }

        return redirect('/');
    }
}