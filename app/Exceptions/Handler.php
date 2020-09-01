<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use App\Exceptions\Auth\TokenNotFoundException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Exception  $exception
     * @return JsonResponse|Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return $this->response('exceptions.page.not_found', null, Response::HTTP_NOT_FOUND);
            }
        }

        if ($e = $this->JWTHandler($exception)) {
            return $e;
        }

        if ($exception instanceof ThrottleRequestsException) {
            return $this->response('exceptions.too_many_requests', null, Response::HTTP_TOO_MANY_REQUESTS);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->response($exception->getMessage(), null, $exception->getCode());
        }

        if ($exception instanceof ValidationException) {
            return $this->response(
                'exceptions.validation',
                ['errors' => $exception->errors()],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof PostTooLargeException) {
            return $this->response('exceptions.validation',
                [
                    'message' => 'validations.max_payload',
                    'value' => ini_get('upload_max_filesize'),
                ],
                Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
        }

        if ($exception instanceof ImportException) {
            return parent::render($request, $exception);
        }

        if ($request->expectsJson()) {
            return $this->response(
                $exception->getMessage() ?: 'exceptions.default',
                null,
                $exception->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }

    /**
     * JWT Handle exceptions
     *
     * @param $exception
     * @return bool|JsonResponse
     */
    protected function JWTHandler($exception)
    {
        if ($exception instanceof UserNotDefinedException) {
            return $this->response('exceptions.jwt.user.not_found', null, Response::HTTP_NOT_FOUND);
        } elseif ($exception instanceof TokenExpiredException) {
            return $this->response('exceptions.jwt.token.expired', null, Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof TokenInvalidException) {
            return $this->response('exceptions.jwt.token.invalid', null, Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof TokenNotFoundException) {
            return $this->response('exceptions.jwt.token.not_found', null, Response::HTTP_UNAUTHORIZED);
        } elseif ($exception instanceof JWTException) {
            return $this->response('exceptions.jwt.default', null, Response::HTTP_UNAUTHORIZED);
        } else {
            return false;
        }
    }
}
