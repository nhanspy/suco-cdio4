<?php

namespace App\Entities;

use App\Notifications\SendMobileResetPasswordEmailNotification;
use App\Notifications\SendUserResetPasswordEmailNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable, HybridRelations;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'language', 'position', 'provider', 'provider_id'
    ];

    protected $attributes = [
        'avatar' => '/images/auth/avatar.jpg',
        'language' => 'en',
        'position' => 'SI1 Studio'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Define user has role or not
     */
    public $hasRole = false;

    /**
     * @param $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return url($value);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SendUserResetPasswordEmailNotification($token));
    }

    public function sendMobilePasswordResetNotification($token)
    {
        $this->notify(new SendMobileResetPasswordEmailNotification($token));
    }

    public function userDevices()
    {
        return $this->hasMany(UserDevice::class);
    }

    /**
     *  archived translations of users
     */
    public function archives()
    {
        return $this->belongsToMany(Translation::class, 'archives', 'user_id', 'translation_id');
    }

    /**
     * liked translations of user
     */
    public function likes()
    {
        return $this->belongsToMany(Translation::class, 'likes', 'user_id', 'translation_id');
    }

    /**
     * searched translation histories of user
     */
    public function histories()
    {
        return $this->belongsToMany(Translation::class, 'search_histories', 'user_id', 'translation_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function pushes()
    {
        return $this->belongsToMany(Push::class, 'push_users', 'user_id', 'push_id');
    }
}
