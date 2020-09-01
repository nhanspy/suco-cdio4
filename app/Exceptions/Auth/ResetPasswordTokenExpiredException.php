<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordTokenExpiredException extends Exception
{
    protected $message = 'exceptions.auth.reset_password.token_expired';

    protected $code = Response::HTTP_BAD_REQUEST;
}
