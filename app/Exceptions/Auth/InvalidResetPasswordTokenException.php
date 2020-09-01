<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidResetPasswordTokenException extends Exception
{
    protected $message = 'exceptions.auth.reset_password.token_invalid';

    protected $code = Response::HTTP_BAD_REQUEST;
}
