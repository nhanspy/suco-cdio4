<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CanResetPasswordException extends Exception
{
    protected $message = 'exceptions.auth.reset_password.forbidden';

    protected $code = Response::HTTP_FORBIDDEN;
}
