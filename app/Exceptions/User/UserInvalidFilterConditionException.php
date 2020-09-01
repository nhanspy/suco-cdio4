<?php

namespace App\Exceptions\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class UserInvalidFilterConditionException extends Exception
{
    protected $message = 'exceptions.user.filter.invalid_condition';

    protected $code = Response::HTTP_BAD_REQUEST;
}
