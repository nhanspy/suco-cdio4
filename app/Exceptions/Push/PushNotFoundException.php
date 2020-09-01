<?php

namespace App\Exceptions\Push;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class PushNotFoundException extends Exception
{
    protected $message = 'exceptions.push.not_found';

    protected $code = Response::HTTP_NOT_FOUND;
}
