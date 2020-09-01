<?php

namespace App\Repositories;

use App\Entities\TranslationStatistic;

class TranslationStatisticRepository extends BaseRepository
{
    public function model()
    {
        return TranslationStatistic::class;
    }
}
