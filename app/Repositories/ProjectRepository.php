<?php

namespace App\Repositories;

use App\Entities\Project;

class ProjectRepository extends BaseRepository
{
    public function model()
    {
        return Project::class;
    }
}
