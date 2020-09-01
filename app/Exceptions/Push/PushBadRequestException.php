<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushBadRequestException extends Exception
{
    protected $message = 'exceptions.push.bad_request';

    protected $code = Response::HTTP_BAD_REQUEST;
}
