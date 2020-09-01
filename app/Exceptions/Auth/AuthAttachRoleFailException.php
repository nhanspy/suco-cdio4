<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthAttachRoleFailException extends Exception
{
    protected $message = 'exceptions.auth.role.attach_fail';

    protected $code = Response::HTTP_BAD_REQUEST;
}
