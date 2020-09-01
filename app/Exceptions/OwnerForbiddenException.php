<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class OwnerForbiddenException extends Exception
{
    protected $message = 'exceptions.owner.forbidden';

    protected $code = Response::HTTP_FORBIDDEN;
}
