<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushDefaultException extends Exception
{
    protected $message = 'exceptions.push.default';

    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
}
