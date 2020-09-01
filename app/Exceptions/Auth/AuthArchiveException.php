<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthArchiveException extends Exception
{
    protected $message = 'exceptions.auth.archive.fail';

    protected $code = Response::HTTP_BAD_REQUEST;
}
