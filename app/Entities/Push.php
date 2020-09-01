<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Push extends Model
{
    protected $fillable = ['device_type', 'device_id', 'channel_id', 'provider'];

    protected $attributes = [
        'provider' => 'airship'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'push_users', 'push_id', 'user_id');
    }
}
