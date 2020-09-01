<?php

namespace App\Repositories;

use App\Entities\Notification;

class NotificationRepository extends BaseRepository
{
    /**
     * Specify Model
     *
     * @return mixed
     */
    public function model()
    {
        return Notification::class;
    }
}
