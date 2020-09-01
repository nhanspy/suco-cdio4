<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TokenNotFoundException extends Exception
{
    protected $message = 'exceptions.jwt.token.not_found';

    protected $code = Response::HTTP_UNAUTHORIZED;
}
