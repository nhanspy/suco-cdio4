<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archive extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'device_id', 'translation_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function translation()
    {
        return $this->belongsTo(Translation::class, 'translation_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'device_id');
    }
}
