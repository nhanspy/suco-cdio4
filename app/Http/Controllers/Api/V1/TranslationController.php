<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Translations\TranslationListRequest;
use App\Http\Requests\TranslationSearchRequest;
use App\Services\TranslationService;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /** @var TranslationService */
    private $translationService;

    public function __construct()
    {
        $this->translationService = app(TranslationService::class);
    }

    public function all(TranslationListRequest $request)
    {
        $perPage = $request->get('perPage');

        $response['translations'] = $this->translationService->all($perPage);

        return $this->response('translation.all.success', $response);
    }

    public function show($translationId)
    {
        $response['translation'] = $this->translationService->show($translationId);

        return $this->response('translation.show.success', $response);
    }

    public function search(TranslationSearchRequest $request)
    {
        $key = $request->get('key', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('perPage');
        $projects = $request->get('projects', []);

        $result = $this->translationService->search($key, $projects, ['phrase', 'meaning'], $page, $perPage);

        $response['translations'] = $result;

        return $this->response('translation.search.success', $response);
    }

    public function topSearch(Request $request)
    {
        $perPage = $request->get('perPage');

        $result = $this->translationService->getTopSearch($perPage);

        return $this->response('top_search.get.success', ['translations' => $result]);
    }
}
