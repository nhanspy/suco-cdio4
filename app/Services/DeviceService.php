<?php

namespace App\Services;

use App\Entities\Device;
use App\Repositories\DeviceRepository;
use Exception;
use Illuminate\Support\Str;

class DeviceService
{
    /** @var DeviceRepository */
    private $deviceRepo;

    /** @var Device */
    private $device;

    /** @var string */
    private $deviceId;

    public function __construct()
    {
        $this->deviceRepo = app(DeviceRepository::class);
    }

    /**
     * @param $deviceId
     * @throws Exception
     */
    public function set($deviceId)
    {
        $this->deviceId = $deviceId;

        $this->device = $this->deviceRepo->firstOrCreate(['device_id' => $this->deviceId]);
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->device->id;
    }

    /**
     * @return string
     */
    public function deviceId()
    {
        return $this->deviceId;
    }

    /**
     * @return string
     */
    public function responseDeviceId()
    {
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    /**
     * @return mixed
     */
    public function archives()
    {
        return $this->device->archives;
    }

    /**
     * @return DeviceRepository
     */
    public function repo()
    {
        return $this->deviceRepo;
    }
}
