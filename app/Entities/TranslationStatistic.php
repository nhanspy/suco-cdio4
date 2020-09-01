<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslationStatistic extends Model
{
    use SoftDeletes;

    protected $fillable = ['translation_id', 'count', 'statistic_at'];
}
