<?php

namespace App\Services\Push;

use App\Exceptions\Push\PushBadRequestException;
use App\Exceptions\Push\PushDefaultException;
use App\Exceptions\Push\PushForbiddenException;
use App\Exceptions\Push\PushNotFoundException;
use App\Exceptions\Push\PushUnauthorizedException;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ExceptionHandler
{
    /**
     * @param $e
     * @throws PushBadRequestException
     * @throws PushForbiddenException
     * @throws PushUnauthorizedException
     * @throws PushDefaultException
     * @throws Exception
     */
    public function handler($e)
    {
        if ($e->getCode() == Response::HTTP_BAD_REQUEST) {
            throw new PushBadRequestException();
        }

        if ($e->getCode() == Response::HTTP_UNAUTHORIZED) {
            throw new PushUnauthorizedException();
        }

        if ($e->getCode() == Response::HTTP_FORBIDDEN) {
            throw new PushForbiddenException();
        }

        if ($e->getCode() === Response::HTTP_NOT_FOUND) {
            throw new PushNotFoundException();
        }

        throw new PushDefaultException($e);
    }
}
