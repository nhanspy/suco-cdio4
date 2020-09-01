<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TranslationHistory extends Model
{
    use SoftDeletes;

    protected $fillable = ['admin_id', 'phrase', 'translation_id', 'meaning', 'description'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function translation()
    {
        return $this->belongsTo(Translation::class);
    }
}
