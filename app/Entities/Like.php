<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['translation_id', 'user_id', 'device_id'];

    protected $table = 'likes';
}
