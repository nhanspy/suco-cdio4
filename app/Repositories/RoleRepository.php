<?php

namespace App\Repositories;

use App\Entities\Role;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }
}
