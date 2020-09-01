<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $fillable = ['admin_id', 'title', 'content'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_notifications');
    }
}
