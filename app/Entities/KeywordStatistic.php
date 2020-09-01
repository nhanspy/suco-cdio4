<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KeywordStatistic extends Model
{
    use SoftDeletes;

    protected $fillable = ['translation_id', 'count', 'created_at'];
}
