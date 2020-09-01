<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushUnauthorizedException;
use App\Http\Requests\Notification\NotificationFilterRequest;
use App\Http\Requests\Notification\StoreNotificationRequest;
use App\Http\Requests\Notification\UpdateNotificationRequest;
use App\Services\NotificationService;
use Exception;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /** @var NotificationService */
    private $notify;

    public function __construct()
    {
        $this->notify = app(NotificationService::class);
    }

    /**
     * @param NotificationFilterRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function all(NotificationFilterRequest $request)
    {
        $perPage = $request->get('perPage');

        $data = $this->notify->all($perPage);

        return $this->response('notification.all.success', $data);
    }

    public function show($id)
    {
        $data = $this->notify->show($id);

        return $this->response('notification.show.success', ['notification' => $data]);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function count()
    {
        $count = $this->notify->count();

        return $this->response('notification.count.success', ['count' => $count]);
    }

    /**
     * @param StoreNotificationRequest $request
     * @return JsonResponse
     */
    public function store(StoreNotificationRequest $request)
    {
        $data = $request->only(['title', 'content', 'projects']);
        $response = $this->notify->store($data);

        return $this->response('notification.store.success', ['notification' => $response]);
    }

    /**
     * @param $id
     * @param UpdateNotificationRequest $request
     * @return JsonResponse
     */
    public function update($id, UpdateNotificationRequest $request)
    {
        $data = $request->only(['title', 'content']);
        $response = $this->notify->update($id, $data);

        return $this->response('notification.update.success', ['notification' => $response]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        $this->notify->delete($id);

        return $this->response('notification.delete.success');
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function restore($id)
    {
        $this->notify->restore($id);

        return $this->response('notification.restore.success');
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function push($id)
    {
        $this->notify->pushNotification($id);

        return $this->response('notification.push.success');
    }

    /**
     * @param StoreNotificationRequest $request
     * @return JsonResponse
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function createAndPush(StoreNotificationRequest $request)
    {
        $data = $request->only(['title', 'content', 'projects']);

        $notification = $this->notify->createAndPush($data);

        return $this->response('notification.create_and_push.success', [ 'notification' => $notification ]);
    }

    /**
     * @param $id
     * @param UpdateNotificationRequest $request
     * @return JsonResponse
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function updateAndPush($id, UpdateNotificationRequest $request)
    {
        $data = $request->only(['title', 'content']);

        $notification = $this->notify->updateAndPush($id, $data);

        return $this->response('notification.update_and_push.success', [ 'notification' => $notification ]);
    }

    /**
     * @param $id
     * @param $projectId
     * @return JsonResponse
     */
    public function attachProject($id, $projectId)
    {
        $this->notify->attachProject($id, $projectId);

        return $this->response('notification.project.attach_success');
    }

    /**
     * @param $id
     * @param $projectId
     * @return JsonResponse
     */
    public function detachProject($id, $projectId)
    {
        $this->notify->detachProject($id, $projectId);

        return $this->response('notification.project.detach_success');
    }
}
