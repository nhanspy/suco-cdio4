<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'description'];

    public function admins()
    {
        return $this->belongsToMany(Admin::class, 'admin_roles');
    }
}
