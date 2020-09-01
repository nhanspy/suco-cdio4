<?php

namespace App\Exceptions\Auth;

use App\Traits\ResponseTrait;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class AuthRegisterEmailUniqueException extends Exception
{
    use ResponseTrait;

    protected $code = Response::HTTP_BAD_REQUEST;
    protected $message = 'exceptions.validations';

    public function render()
    {
        return $this->response($this->message, [
           'errors' => [
               'email' => ['validations.email.unique']
           ]
        ], $this->code);
    }
}
