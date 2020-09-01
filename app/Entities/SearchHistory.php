<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SearchHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'translation_id', 'device_id', 'keyword'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
