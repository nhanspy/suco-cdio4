<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthDetachRoleFailException extends Exception
{
    protected $message = 'exceptions.auth.role.detach_fail';

    protected $code = Response::HTTP_BAD_REQUEST;
}
