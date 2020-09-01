<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthDeleteLikeException extends Exception
{
    protected $message = 'exceptions.auth.like.delete.fail';
    protected $code = Response::HTTP_BAD_REQUEST;
}
