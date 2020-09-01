<?php

namespace App\Repositories;

use App\Entities\Archive;

class ArchiveRepository extends BaseRepository
{
    public function model()
    {
        return Archive::class;
    }
}
