<?php

namespace Ipunkt\Laravel\SocialiteProfiles;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var Router
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param  Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->router->group(['middleware' => ['web']], function (Router $router) {
            $router->get(config('socialite-profiles.route', '/authenticate/with/') . '{provider}', [
                'as' => 'social.login',
                'uses' => '\Ipunkt\Laravel\SocialiteProfiles\Http\Controllers\SocialLoginController@authenticateWith'
            ]);

            $router->delete(config('socialite-profiles.detaching_route', '/detach/profile/') . '{provider}', [
                'as' => 'social.detach',
                'uses' => '\Ipunkt\Laravel\SocialiteProfiles\Http\Controllers\SocialProfileController@destroy'
            ]);
        });
    }
}
