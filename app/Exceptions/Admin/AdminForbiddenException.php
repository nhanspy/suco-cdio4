<?php

namespace App\Exceptions\Admin;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class AdminForbiddenException extends Exception
{
    protected $message = 'exceptions.admin.forbidden';

    protected $code = Response::HTTP_FORBIDDEN;
}
