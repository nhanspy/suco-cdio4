<?php

namespace App\Services;

use App\Entities\TranslationStatistic;
use App\Repositories\SearchHistoryRepository;
use App\Repositories\TranslationRepository;
use App\Repositories\TranslationStatisticRepository;
use App\Services\Auth\AuthService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

class StatisticService
{
    /** @var AuthService */
    private $auth;

    /** @var SearchHistoryRepository */
    private $searchHisRepo;

    /** @var TranslationRepository */
    private $translationRepo;

    /** @var TranslationStatisticRepository */
    private $translationStatRepo;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
        $this->searchHisRepo = app(SearchHistoryRepository::class);
        $this->translationRepo = app(TranslationRepository::class);
        $this->translationStatRepo = app (TranslationStatisticRepository::class);
    }

    public function save($keyword, $translationId)
    {
        $translation = $this->translationRepo->findOrFail($translationId);

        $data = [
            'translation_id' => $translationId,
            'keyword' => $keyword,
            // 'device_id' => $this->auth->deviceToken()
            'device_id' => null
        ];

        if ($this->auth->check()) {
            $data['user_id'] = $this->auth->id();
        }

        $result = $this->searchHisRepo->create($data);

        $translation->total_search = $this->searchHisRepo->countByTranslation($translation->id);
        $translation->save();

        return $result;
    }
}
