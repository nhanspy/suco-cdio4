<?php

namespace App\Services;

use App\Repositories\ProjectRepository;
use App\Services\Auth\AuthService;
use Exception;

class ProjectService
{
    /** @var AuthService */
    private $auth;

    /** @var ProjectRepository */
    private $projectRepo;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
        $this->projectRepo = app(ProjectRepository::class);
    }

    public function all($perPage = null, $deleted = false)
    {
        $this->projectRepo->orderBy('id', 'DESC');

        if ($deleted) {
            $this->projectRepo->onlyTrashed();
        }

        return $this->projectRepo->paginate($perPage);
    }

    public function show($projectId)
    {
        return $this->projectRepo->with('admin')->findOrFail($projectId);
    }

    public function search($key, $deleted = false, $perPage = null)
    {
        if ($deleted) {
            $this->projectRepo->onlyTrashed();
        }

        return $this->projectRepo->whereLike('name', $key)->paginate($perPage);
    }

    public function create($projectData)
    {
        $projectData['admin_id'] = $this->auth->guard('admin')->id();

        return $this->projectRepo->create($projectData);
    }

    public function update($projectId, $updateData)
    {
        return $this->projectRepo->update($projectId, $updateData);
    }

    public function delete($projectId)
    {
        $this->projectRepo->findOrFail($projectId);

        $result = $this->projectRepo->delete($projectId);

        return $result;
    }

    public function getTranslation($projectId, $perPage = null)
    {
        $project = $this->projectRepo->findOrFail($projectId);

        return $project->translations()->orderBy('id', 'desc')->paginate($perPage);
    }

    public function restore($projectId)
    {
        $project = $this->projectRepo->onlyTrashed()->findOrFail($projectId);

        return $project->restore();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        return $this->projectRepo->all()->count();
    }

    /**
     * Get all projects with no paginate
     * use for filter project in admin
     *
     * @return mixed
     * @throws Exception
     */
    public function getAllNoPaginate()
    {
        return $this->projectRepo->all();
    }
}
