<?php

namespace App\Repositories;

use App\Entities\Device;

class DeviceRepository extends BaseRepository
{
    public function model()
    {
        return Device::class;
    }
}
