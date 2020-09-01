<?php

namespace App\Entities;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Comment extends Eloquent
{
    use SoftDeletes;

    protected $connection = 'mongodb';

    protected $collection = 'comments';

    protected $fillable = ['user_id', 'translation_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }
}
