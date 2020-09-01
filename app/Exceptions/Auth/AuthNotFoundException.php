<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthNotFoundException extends Exception
{
    protected $message = 'exceptions.auth.not_found';
    protected $code = Response::HTTP_BAD_REQUEST;
}
