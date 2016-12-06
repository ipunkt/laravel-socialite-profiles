<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Exceptions;

class SocialiteException extends \Exception
{
    /**
     * no socialite provider set
     *
     * @throws static
     */
    public static function noProviderSet()
    {
        throw new static('No provider for socialite set');
    }
}