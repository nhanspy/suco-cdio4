<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['admin_id', 'name', 'description'];

    protected $hidden = ['deleted_at'];

    protected $attributes = [
        'description' => ''
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'project_notifications');
    }

    public function translations()
    {
        return $this->hasMany(Translation::class);
    }
}
