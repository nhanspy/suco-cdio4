<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushUnauthorizedException;
use App\Http\Requests\Push\CreatePushRequest;
use App\Http\Requests\Push\DeletePushRequest;
use App\Services\Push\PushService;
use Illuminate\Http\JsonResponse;

class PushController extends Controller
{
    /** @var PushService */
    private $push;

    public function __construct()
    {
        $this->push = app(PushService::class);
    }

    /**
     * @param CreatePushRequest $request
     * @return JsonResponse
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function create(CreatePushRequest $request)
    {
        $data = $request->only(['device_type', 'channel_id']);

        $this->push->create($data);

        return $this->response('push.create.success');
    }

    /**
     * @param DeletePushRequest $request
     * @return JsonResponse
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function delete(DeletePushRequest $request)
    {
        $data = $request->only(['device_type', 'channel_id']);

        $this->push->delete($data);

        return $this->response('push.delete.success');
    }
}
