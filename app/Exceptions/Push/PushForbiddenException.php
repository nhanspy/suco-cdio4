<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushForbiddenException extends Exception
{
    protected $message = 'exceptions.push.forbidden';

    protected $code = Response::HTTP_FORBIDDEN;
}
