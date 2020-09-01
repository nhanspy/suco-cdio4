<?php

namespace App\Repositories;

use App\Entities\Like;

class LikeRepository extends BaseRepository
{
    public function model()
    {
        return Like::class;
    }
}
