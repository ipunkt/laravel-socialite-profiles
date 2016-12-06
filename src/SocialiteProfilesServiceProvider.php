<?php

namespace Ipunkt\Laravel\SocialiteProfiles;

use Illuminate\Routing\Router;
use Ipunkt\Laravel\PackageManager\PackageServiceProvider;
use Ipunkt\Laravel\PackageManager\Support\DefinesConfigurations;
use Ipunkt\Laravel\PackageManager\Support\DefinesMigrations;
use Ipunkt\Laravel\PackageManager\Support\DefinesRouteRegistrar;
use Ipunkt\Laravel\PackageManager\Support\DefinesViews;
use Ipunkt\Laravel\SocialiteProfiles\Repositories\SocialConnectionsRepository;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;

class SocialiteProfilesServiceProvider extends PackageServiceProvider implements
    DefinesRouteRegistrar,
    DefinesViews,
    DefinesMigrations,
    DefinesConfigurations
{
    /**
     * returns namespace of package
     *
     * @return string
     */
    protected function namespace()
    {
        return 'socialite-profiles';
    }

    /**
     * defines routes by using the router
     *
     * @param \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function registerRoutesWithRouter(Router $router)
    {
        (new RouteRegistrar($router))->all();
    }

    /**
     * returns view file paths
     *
     * @return array|string[]
     */
    public function views()
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'views',
        ];
    }

    /**
     * returns an array of migration paths
     *
     * @return array|string[]
     */
    public function migrations()
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
        ];
    }

    /**
     * returns an array of config files with their corresponding config_path(name)
     *
     * @return array
     */
    public function configurationFiles()
    {
        return [
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'socialite-profiles.php' => $this->namespace(),
        ];
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        if (class_exists('\SocialiteProviders\Manager\ServiceProvider')) {
            $this->app->register('\SocialiteProviders\Manager\ServiceProvider');
        } else {
            $this->app->register('\Laravel\Socialite\SocialiteServiceProvider');
        }

        $this->registerEventListeners();
    }

    private function registerEventListeners()
    {
        /** @var SocialConnectionsRepository $repository */
        $repository = $this->app->make(SocialConnectionsRepository::class);

        // update all redirect urls
        collect($repository->all())->map(function ($config, $provider) {
            config([
                'services.' . $provider . '.redirect' => url(
                    config('social-login.route', '/authenticate/with/') . $provider, [], ! app()->environment('local')
                )
            ]);
        });

        /** @var DispatcherContract $events */
        $events = app('events');

        $repository->listenForHandledProviders($events);
    }
}