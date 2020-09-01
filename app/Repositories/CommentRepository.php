<?php

namespace App\Repositories;

use App\Entities\Comment;

class CommentRepository extends BaseRepository
{
    public function model()
    {
        return Comment::class;
    }
}
