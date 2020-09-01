<?php

namespace App\Http\Middleware;

use App\Exceptions\Auth\DeviceIdAuthenticateException;
use App\Services\Auth\AuthService;
use App\Services\DeviceService;
use Closure;
use Illuminate\Http\Request;

class DeviceIdAuthenticate
{
    /** @var AuthService */
    private $auth;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
    }
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $guard
     * @return mixed
     * @throws DeviceIdAuthenticateException
     */
    public function handle($request, Closure $next, $guard = '')
    {
        if (!$guard && $this->auth->check()) {
            return $next($request);
        }

        if (!$deviceId = $request->header('Device-Id')) {
            throw new DeviceIdAuthenticateException();
        }

        $device = app(DeviceService::class);

        $device->set($deviceId);

        return $next($request);
    }
}
