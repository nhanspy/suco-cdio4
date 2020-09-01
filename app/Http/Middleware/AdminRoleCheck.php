<?php

namespace App\Http\Middleware;

use App\Exceptions\Admin\AdminForbiddenException;
use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminRoleCheck
{
    use ResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     * @throws AdminForbiddenException
     */
    public function handle($request, Closure $next, $role)
    {
        $response = $next($request);

        $guard = config('auth.guard.admin');

        $roles = Auth::guard($guard)->user()->roles->map(function ($user) {
            return $user->name;
        })->toArray();

        if (! in_array($role, $roles)) {
            throw new AdminForbiddenException();
        }

        return $response;
    }
}
