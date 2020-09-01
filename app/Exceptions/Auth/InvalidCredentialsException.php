<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends Exception
{
    protected $message = 'exceptions.auth.credentials.invalid';

    protected $code = Response::HTTP_BAD_REQUEST;
}
