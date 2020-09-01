<?php

namespace App\Repositories;

use App\Entities\Push;

class PushRepository extends BaseRepository
{
    public function model()
    {
        return Push::class;
    }
}
