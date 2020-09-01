<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class LoginInvalidCredentialsException extends Exception
{
    protected $message = 'exceptions.auth.login.invalid_credentials';

    protected $code = Response::HTTP_BAD_REQUEST;
}
