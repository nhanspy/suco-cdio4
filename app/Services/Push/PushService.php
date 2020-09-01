<?php

namespace App\Services\Push;

use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushDefaultException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushUnauthorizedException;
use App\Repositories\PushRepository;
use App\Services\Auth\AuthService;
use App\Services\DeviceService;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class PushService
{
    /** @var PushRepository */
    private $pushRepo;

    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->pushRepo = app(PushRepository::class);

        $this->client = new Client([
            'base_uri' => config('services.push.host'),
            'headers' => [
                'Authorization' => config('services.push.token'),
                'Accept' => config('services.push.headers.accept'),
                'Content-Type' => config('services.push.headers.content_type')
            ]
        ]);
    }

    /**
     * @return AuthService
     */
    public function auth()
    {
        return app(AuthService::class);
    }

    /**
     * @return DeviceService
     */
    private function device()
    {
        return app(DeviceService::class);
    }

    /**
     * @return NamedUserService
     */
    private function namedUser()
    {
        return app(NamedUserService::class);
    }

    /**
     * @return ExceptionHandler
     */
    private function exception()
    {
        return app(ExceptionHandler::class);
    }

    /**
     * @param $message
     * @return bool
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     * @throws PushDefaultException
     */
    public function push($message)
    {
        $data = [
            "audience" => "all",
            "notification" => [
                "alert" => $message
            ],
            "device_types" => [ "ios", "android" ]
        ];

        try {
            $this->client->post(config('services.push.push_uri'), [ 'json' => $data ]);
        } catch (Exception $e) {
            $this->exception()->handler($e);
        }

        return true;
    }

    /**
     * @param $data
     * @return PushRepository|Model
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function create($data)
    {
        $this->namedUser()->associate($data['channel_id'], $this->auth()->id());

        $data['device_id'] = $this->device()->deviceId();
        $data['provider'] = config('services.push.provider');

        $push = $this->pushRepo->firstOrCreate($data);

        if (!$this->auth()->user()->pushes->contains($push->id)) {
            $push->users()->attach($this->auth()->id());
        }

        return $push;
    }

    /**
     * @param $data
     * @return bool
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     */
    public function delete($data)
    {
        $this->namedUser()->disassociate($data['channel_id'], $data['device_type'], $this->auth()->id());

        $push = $this->pushRepo->firstOrFail('channel_id', $data['channel_id']);

        if ($this->auth()->user()->pushes->contains($push->id)) {
            $this->auth()->user()->pushes()->detach($push->id);
        }

        return true;
    }
}
