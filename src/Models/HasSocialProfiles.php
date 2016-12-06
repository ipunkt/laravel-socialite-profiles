<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Models;

trait HasSocialProfiles
{
    /**
     * users social profiles
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function profiles()
    {
        return $this->hasMany(UserSocialProfile::class, 'user_id', 'id');
    }
}