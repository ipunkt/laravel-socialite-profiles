<?php

namespace Ipunkt\Laravel\SocialiteProfiles\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSocialProfile
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $provider
 * @property string $uid
 * @property string $nickname
 * @property string $name
 * @property string $email
 * @property string $avatar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Ipunkt\Laravel\SocialiteProfiles\Models\UserSocialProfile whereUpdatedAt($value)
 */
class UserSocialProfile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_social_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'provider', 'uid', 'nickname', 'name', 'email', 'avatar'];

    /**
     * does this user belongs to the project
     *
     * @return bool
     */
    public function user()
    {
        return $this->belongsTo(config('socialite-profiles.user-model', '\App\User'));
    }
}