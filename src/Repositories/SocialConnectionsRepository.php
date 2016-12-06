<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Repositories;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Collection;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialConnectionsRepository
{
    /**
     * configured services
     *
     * @var array|mixed
     */
    private $services = [];

    /**
     * all handlers for providers
     *
     * @var array
     */
    private $handlers = [];

    /**
     * SocialConnectionsRepository constructor.
     *
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $services = $config->get('services', []);
        foreach ($services as $key => $config) {
            if ( ! $this->isConfigured($config)) {
                continue;
            }
            $this->services[$key] = $config;

            $handler = array_get($config, 'handler');
            if ($handler === null) {
                continue;
            }
            list($class,) = explode('@', $handler);
            if (!class_exists($class)) {
                unset($this->services[$key]);
                continue;
            }

            $this->handlers[$key] = $handler;
        }
    }

    /**
     * returns all social connections, configured in /config/services.php
     *
     * @return array
     */
    public function all()
    {
        $services = $this->services;

        $providers = [];
        foreach ($services as $key => $config) {
            $providers[$key] = $config;
            $providers[$key]['title'] = (array_key_exists('title', $config)) ? $config['title'] : ucwords($key);
            $providers[$key]['order'] = (array_key_exists('order',
                $config)) ? intval($config['order']) : count($providers);
        }

        return $providers;
    }

    /**
     * returns all connections for login
     *
     * @param Collection $except
     *
     * @return \Illuminate\Support\Collection
     */
    public function allForLogin($except = null)
    {
        $result = $this->only(['groups' => 'login']);

        if ($except !== null) {
            $result = $result->filter(function ($config, $provider) use ($except) {
                return ! in_array($provider, $except->all());
            });
        }

        return $result;
    }

    /**
     * check if service is configured
     *
     * @param array $service
     *
     * @return bool
     */
    public function isConfigured($service)
    {
        if ( ! array_key_exists('client_id', $service) || empty($service['client_id'])) {
            return false;
        }
        if ( ! array_key_exists('client_secret', $service) || empty($service['client_secret'])) {
            return false;
        }
        if ( ! array_key_exists('redirect', $service) || empty($service['redirect'])) {
            return false;
        }

        return true;
    }

    /**
     * returns all connections with filter
     *
     * @param array $filter
     * @param array $except
     *
     * @return \Illuminate\Support\Collection
     */
    public function only(array $filter, array $except = [])
    {
        $services = collect($this->all());

        return $services->filter(function ($config) use ($except) {
            foreach ($except as $key => $value) {
                if (array_key_exists($key, $config)) {
                    if ( ! is_array($value)) {
                        $value = [$value];
                    }

                    foreach ($value as $v) {
                        if (in_array($v, $config[$key])) {
                            return false;
                        }
                    }
                }
            }

            return true;
        })->filter(function ($config) use ($filter) {
            foreach ($filter as $key => $value) {
                if (array_key_exists($key, $config)) {

                    if ( ! is_array($value)) {
                        $value = [$value];
                    }

                    foreach ($value as $v) {
                        if (in_array($v, $config[$key])) {
                            return true;
                        }
                    }
                }
            }

            return false;
        })->map(function ($config, $key) {
            return array_merge($config,
                ['title' => (array_key_exists('title', $config)) ? $config['title'] : ucwords($key)]);
        })->sortBy('order');
    }

    /**
     * returns all providers
     *
     * @return array
     */
    public function providers() : array
    {
        return array_keys($this->services);
    }

    /**
     * has provider
     *
     * @param string $provider
     *
     * @return bool
     */
    public function hasProvider($provider)
    {
        return array_key_exists($provider, $this->services);
    }

    /**
     * setup listener for social was called event
     *
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function listenForHandledProviders(Dispatcher $events)
    {
        foreach ($this->handlers as $handler) {
            $events->listen(SocialiteWasCalled::class, $handler);
        }
    }
}