<?php

namespace App\Exceptions\Auth;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthDeleteArchiveException extends Exception
{
    protected $message = 'exceptions.auth.archive.delete.fail';
    protected $code = Response::HTTP_BAD_REQUEST;
}
