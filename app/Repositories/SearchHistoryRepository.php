<?php

namespace App\Repositories;

use App\Entities\SearchHistory;
use App\Entities\TranslationHistory;

class SearchHistoryRepository extends BaseRepository
{
    public function model()
    {
        return SearchHistory::class;
    }

    public function countByTranslation($id)
    {
        return $this->model->where('translation_id', $id)->count();
    }
}
