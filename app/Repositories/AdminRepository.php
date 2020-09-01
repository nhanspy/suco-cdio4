<?php

namespace App\Repositories;

use App\Entities\Admin;

class AdminRepository extends BaseRepository
{
    public function model()
    {
        return Admin::class;
    }
}
