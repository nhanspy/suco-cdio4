<?php

namespace App\Services\Push;

use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushDefaultException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushUnauthorizedException;
use Exception;
use GuzzleHttp\Client;

class NamedUserService
{
    /** @var Client */
    private $client;

    public function __construct()
    {
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
     * @return ExceptionHandler
     */
    private function exception()
    {
        return app(ExceptionHandler::class);
    }

    /**
     * @param $channelId
     * @param $userId
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     * @throws PushDefaultException
     */
    public function associate($channelId, $userId)
    {
        $data = [
          'channel_id' => $channelId,
          'named_user_id' => 'user-'.$userId
        ];

        try {
            $this->client->post(config('services.push.associate_uri'), [ 'json' => $data ]);
        } catch (Exception $e) {
            $this->exception()->handler($e);
        }
    }

    /**
     * @param $channelId
     * @param $deviceType
     * @param $userId
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     * @throws PushDefaultException
     */
    public function disassociate($channelId, $deviceType, $userId)
    {
        $data = [
            'channel_id' => $channelId,
            'device_type' => $deviceType,
            'named_user_id' => 'user-'.$userId
        ];

        try {
            $this->client->post(config('services.push.disassociate_uri'), [ 'json' => $data ]);
        } catch (Exception $e) {
            $this->exception()->handler($e);
        }
    }
}
