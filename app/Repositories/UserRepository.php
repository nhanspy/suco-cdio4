<?php

namespace App\Repositories;

use App\Entities\User;

class UserRepository extends BaseRepository
{
    public function model()
    {
        return User::class;
    }
}
