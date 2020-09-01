<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthChangePasswordException extends Exception
{
    protected $code = Response::HTTP_BAD_REQUEST;

    protected $message = 'auth.change_password.invalid_password';
}
