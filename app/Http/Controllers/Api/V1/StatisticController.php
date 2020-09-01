<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\StatisticRequest;
use App\Services\StatisticService;

class StatisticController extends Controller
{
    /** @var StatisticService */
    private $statisticService;

    public function __construct()
    {
        $this->statisticService = app(StatisticService::class);
    }

    public function collectData(StatisticRequest $request)
    {
        $keyword = $request->get('key');
        $translationId = $request->get('translation_id');

        $this->statisticService->save($keyword, $translationId);

        return $this->response('collect_data.success');
    }
}
