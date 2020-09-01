<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidGuardException extends Exception
{
    protected $message = 'exceptions.auth.guard.invalid';

    protected $code = Response::HTTP_FORBIDDEN;
}
