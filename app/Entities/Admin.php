<?php

namespace App\Entities;

use App\Notifications\SendAdminResetPasswordEmailNotification;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use SoftDeletes, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'language', 'position'
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
     * Define default role of admin
     */
    public $defaultRole = 'SuperAdmin';

    /**
     * Define admin has role or not
     */
    public $hasRole = true;

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

    /**
     * Sends reset password email
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SendAdminResetPasswordEmailNotification($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_roles');
    }

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }

    public function translationHistories()
    {
        return $this->hasMany(TranslationHistory::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
