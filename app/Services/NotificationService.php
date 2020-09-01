<?php

namespace App\Services;

use App\Entities\Notification;
use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushUnauthorizedException;
use App\Repositories\NotificationRepository;
use App\Services\Auth\AuthService;
use App\Services\Push\PushService;
use Exception;

class NotificationService
{
    /** @var NotificationRepository */
    private $notifyRepo;

    public function __construct()
    {
        $this->notifyRepo = app(NotificationRepository::class);
    }

    /**
     * @return PushService
     */
    public function push()
    {
        return app(PushService::class);
    }

    /**
     * @return AuthService
     */
    private function auth()
    {
        return app(AuthService::class);
    }

    /**
     * @param $perPage
     * @return array
     * @throws Exception
     */
    public function all($perPage = null)
    {
        $pagination = $this->notifyRepo->with('admin')
                                        ->with('projects')
                                        ->orderBy('created_at', 'desc')
                                        ->paginate($perPage)
                                        ->toArray();

        $count = $this->notifyRepo->all()->count();

        $notifications = $pagination['data'];

        unset($pagination['data']);

        return [
            'notifications' => $notifications,
            'pagination' => $pagination,
            'count' => $count
        ];
    }

    /**
     * @param $id
     * @return Notification
     */
    public function show($id)
    {
        return $this->notifyRepo->with('projects')->findOrFail($id);
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function count()
    {
        return $this->notifyRepo->all()->count();
    }

    /**
     * @param $data
     * @return Notification
     */
    public function store($data)
    {
        $data['admin_id'] = $this->auth()->id();

        $notify = $this->notifyRepo->create($data);

        if (isset($data['projects'])) {
            $ids = collect($data['projects'])->map(function ($project) {
                return $project['id'];
            })->toArray();

            $notify->projects()->attach($ids);
        }

        $notify->projects;
        $notify->admin;

        return $notify;
    }

    /**
     * @param $id
     * @param $data
     * @return Notification
     */
    public function update($id, $data)
    {
        $this->notifyRepo->update($id, $data);

        return $this->notifyRepo->find($id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        $this->notifyRepo->findOrFail($id);

        return $this->notifyRepo->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        return $this->notifyRepo->restore($id);
    }

    /**
     * Push notification to all devices
     *
     * @param $id
     * @return bool
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function pushNotification($id)
    {
        $notify = $this->notifyRepo->findOrFail($id);

        return $this->push()->push($notify->content);
    }

    /**
     * @param $data
     * @return Notification
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function createAndPush($data)
    {
        $notify = $this->store($data);

        $this->push()->push($notify->content);

        return $notify;
    }

    /**
     * @param $id
     * @param $data
     * @return Notification
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function updateAndPush($id, $data)
    {
        $notify = $this->update($id, $data);

        $this->push()->push($notify->content);

        return $notify;
    }

    /**
     * @param $id
     * @param $projectId
     * @return mixed
     */
    public function attachProject($id, $projectId)
    {
        return $this->notifyRepo->findOrFail($id)->projects()->attach($projectId);
    }

    /**
     * @param $id
     * @param $projectId
     * @return mixed
     */
    public function detachProject($id, $projectId)
    {
        return $this->notifyRepo->findOrFail($id)->projects()->detach($projectId);
    }
}
