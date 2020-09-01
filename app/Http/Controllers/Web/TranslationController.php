<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Comment\ListCommentRequest;
use App\Http\Requests\ExportTranslationRequest;
use App\Http\Requests\ImportTranslationRequest;
use App\Http\Requests\Translations\TranslationCreateRequest;
use App\Http\Requests\Translations\TranslationListRequest;
use App\Http\Requests\Translations\TranslationUpdateRequest;
use App\Http\Requests\TranslationSearchRequest;
use App\Services\CommentService;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslationController extends Controller
{
    /** @var TranslationService */
    private $translationService;

    /** @var CommentService */
    private $commentService;

    public function __construct()
    {
        $this->translationService = app(TranslationService::class);
        $this->commentService = app(CommentService::class);
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

    public function addToIndex()
    {
        $this->translationService->addAllToIndex();

        return $this->response('elastic.add_all_to_index_success');
    }

    public function elasticIndex()
    {
        $typeExists = $this->translationService->typeExists();

        if ($typeExists) {
            return $this->response('exceptions.already_index', null, Response::HTTP_BAD_REQUEST);
        }

        $response = $this->translationService->createIndex();

        return $this->response('elastic.create_index.success', $response);
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


    public function create(TranslationCreateRequest $request)
    {
        $translationData = $request->all();
        $projectId = $request->get('project_id');

        $response['translation'] = $this->translationService->create($projectId, $translationData);

        return $this->response('translation.create.success', $response);
    }

    public function update(TranslationUpdateRequest $request, $translationId)
    {
        $updateData = $request->only(['phrase', 'meaning', 'description']);

        $response['translation'] = $this->translationService->update($translationId, $updateData);

        return $this->response('translation.update.success', $response);
    }

    public function delete($translationId)
    {
        $this->translationService->delete($translationId);

        return $this->response('translation.delete.success');
    }

    public function importExcel(ImportTranslationRequest $request)
    {
        $uploadedFile = $request->file('file');

        $filePath = $this->translationService->uploadFile($uploadedFile);

        $this->translationService->import($filePath);

        return $this->response('translation.import.success');
    }


    public function exportExcel(ExportTranslationRequest $request)
    {
        $projects = $request->get('projects', []);
        $template = $request->get('template', false);

        if ($template) {
            return $this->translationService->exportTemplate();
        }

        return $this->translationService->exportProject($projects);
    }

    public function topSearch(TranslationListRequest $request)
    {
        $perPage = $request->get('perPage');

        $result = $this->translationService->getTopSearch($perPage);

        return $this->response('top_search.get.success', ['translations' => $result]);
    }

    public function topLike(TranslationListRequest $request)
    {
        $perPage = $request->get('perPage');

        $result = $this->translationService->getTopLike($perPage);

        return $this->response('top_like.get.success', ['translations' => $result]);
    }

    public function topComment(TranslationListRequest $request)
    {
        $perPage = $request->get('perPage');

        $result = $this->translationService->getTopComment($perPage);

        return $this->response('top_comment.get.success', ['translations' => $result]);
    }

    public function recentSearch(TranslationListRequest $request)
    {
        $perPage = $request->get('perPage');

        $result = $this->translationService->getRecentSearch($perPage);

        return $this->response('recent_search.get.success', ['recent' => $result]);
    }

    public function getComment(ListCommentRequest $request, $translationId)
    {
        $perPage = $request->get('perPage');

        $response['comments'] = $this->commentService->getByTranslation($translationId, $perPage);

        return $this->response('comment.get.success', $response);
    }
}
