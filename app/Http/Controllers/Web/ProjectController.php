<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests\Projects\ProjectListRequest;
use App\Http\Requests\Projects\ProjectCreateRequest;
use App\Http\Requests\Projects\ProjectUpdateRequest;
use App\Http\Requests\Projects\ProjectSearchRequest;
use App\Services\ProjectService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    /** @var ProjectService */
    private $projectService;

    public function __construct()
    {
        $this->projectService = app(ProjectService::class);
    }

    public function all(ProjectListRequest $request)
    {
        $perPage = $request->get('perPage');
        $deleted = $request->get('deleted', false);

        // Parse string to boolean
        $deleted = ($deleted === 'true');

        $response['projects'] = $this->projectService->all($perPage, $deleted);

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

    public function create(ProjectCreateRequest $request)
    {
        $projectData = $request->only(['name', 'description']);

        $response['project'] = $this->projectService->create($projectData);

        return $this->response('project.create.success', $response);
    }

    public function update(ProjectUpdateRequest $request, $projectId)
    {
        $updateData = $request->only(['name', 'description']);

        $response['project'] = $this->projectService->update($projectId, $updateData);

        return $this->response('project.update.success', $response);
    }

    public function delete($projectId)
    {
        $this->projectService->delete($projectId);

        return $this->response('project.delete.success');
    }

    public function restore($projectId)
    {
        $this->projectService->restore($projectId);

        return $this->response('project.restore.success');
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function count()
    {
        $count = $this->projectService->count();

        return $this->response('project.count.success', [ 'count' => $count ]);
    }

    /**
     * @throws Exception
     */
    public function getAllNoPaginate()
    {
        $projects = $this->projectService->getAllNoPaginate();

        return $this->response('project.all.success', ['projects' => $projects]);
    }
}
