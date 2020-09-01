<?php

namespace App\Http\Middleware;

use App\Exceptions\Auth\InvalidGuardException;
use App\Exceptions\Auth\TokenNotFoundException;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthentication
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @param  $guard
     * @return mixed
     * @throws UserNotDefinedException
     * @throws InvalidGuardException
     * @throws TokenNotFoundException
     */
    public function handle($request, Closure $next, $guard = '')
    {
        if (! JWTAuth::parser()->setRequest($request)->hasToken()) {
            throw new TokenNotFoundException();
        }

        if (! $user = JWTAuth::parseToken()->authenticate()) {
            throw new UserNotDefinedException();
        }

        if (isset($guard) && ! Auth::guard($guard)->check()) {
            throw new InvalidGuardException();
        }

        return $next($request);
    }
}
