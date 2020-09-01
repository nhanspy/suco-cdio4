<?php

namespace App\Repositories;

use App\Entities\TranslationHistory;

class TranslationHistoryRepository extends BaseRepository
{
    public function model()
    {
        return TranslationHistory::class;
    }
}
