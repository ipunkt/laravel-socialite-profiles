<?php

return [
    /**
     * where to redirect to when user already logged in
     */
    'redirect-when-user-logged-in' => '/home',

    /**
     * User model
     */
    'user-model' => \App\User::class,

    /**
     * The route prefix for the social provider
     */
    'route' => '/authenticate/with/',

    /**
     * forces schema for links
     */
    'force_schema' => 'https',

    /**
     * detaching route
     */
    'detaching_route' => '/detach/profile/',
];