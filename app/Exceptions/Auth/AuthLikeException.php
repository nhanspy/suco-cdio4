<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthLikeException extends Exception
{
    protected $message = 'exceptions.auth.like.fail';
    protected $code = Response::HTTP_BAD_REQUEST;
}
