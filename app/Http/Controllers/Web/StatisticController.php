<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\StatisticRequest;
use App\Services\CommentService;
use App\Services\StatisticService;
use App\Services\TranslationService;
use App\Services\UserService;

class StatisticController extends Controller
{
    /** @var StatisticService */
    private $statisticService;

    /** @var UserService */
    private $userService;

    /** @var TranslationService */
    private $translationService;

    /** @var CommentService */
    private $commentService;

    public function __construct()
    {
        $this->statisticService = app(StatisticService::class);
        $this->userService = app(UserService::class);
        $this->translationService = app(TranslationService::class);
        $this->commentService = app(CommentService::class);
    }

    public function collectData(StatisticRequest $request)
    {
        $keyword = $request->get('key');
        $translationId = $request->get('translation_id');

        $this->statisticService->save($keyword, $translationId);

        return $this->response('collect_data.success');
    }

    public function statistic()
    {
        $result = [
            'total_user' => $this->userService->count(),
            'total_translation' => $this->translationService->count(),
            'total_comment' => $this->commentService->count(),
            'total_search' => $this->translationService->searchCount(),
        ];

        return $this->response('statistic.general', $result);
    }
}
