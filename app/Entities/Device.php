<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use SoftDeletes;

    protected $fillable = ['device_id'];

    public function archives()
    {
        return $this->belongsToMany(Translation::class, 'archives', 'device_id', 'translation_id');
    }

    public function histories()
    {
        return $this->belongsToMany(Translation::class, 'search_histories', 'device_id', 'translation_id');
    }

    public function likes()
    {
        return $this->belongsToMany(Translation::class, 'likes', 'device_id', 'translation_id');
    }
}
