<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushNotificationFailException extends Exception
{
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = 'exceptions.push_notification.fail';
}
