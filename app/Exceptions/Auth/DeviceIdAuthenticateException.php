<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class DeviceIdAuthenticateException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'exceptions.request.headers.required_device_id';
}
