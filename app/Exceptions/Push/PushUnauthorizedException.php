<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushUnauthorizedException extends Exception
{
    protected $message = 'exceptions.push.unauthorized';

    protected $code = Response::HTTP_UNAUTHORIZED;
}
