<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Projects\ProjectListRequest;
use App\Http\Requests\Projects\ProjectCreateRequest;
use App\Http\Requests\Projects\ProjectUpdateRequest;
use App\Http\Requests\Projects\ProjectSearchRequest;
use App\Repositories\ProjectRepository;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    /**
     * @var ProjectRepository
     */
    private $projectRepo;

    /** @var ProjectService */
    private $projectService;

    public function __construct()
    {
        $this->projectRepo = app(ProjectRepository::class);
        $this->projectService = app(ProjectService::class);
    }

    public function all(ProjectListRequest $request)
    {
        $perPage = $request->get('perPage');

        $response['projects'] = $this->projectService->all($perPage);

        return $this->response('project.all.success', $response);
    }

    public function show($projectId)
    {
        $response['project'] = $this->projectService->show($projectId);

        return $this->response('project.info.success', $response);
    }

    public function search(ProjectSearchRequest $request)
    {
        $key = $request->get('key', '');
        $perPage = $request->get('perPage');
        $deleted = $request->get('deleted', false);

        // Parse string to boolean
        $deleted = ($deleted === 'true');

        $response['projects'] = $this->projectService->search($key, $deleted, $perPage);

        return $this->response('project.search.success', $response);
    }

    public function getTranslations($projectId)
    {
        $translation = $this->projectService->getTranslation($projectId);

        $response['translations'] = $translation;

        return $this->response('project.translation.success', $response);
    }
}
