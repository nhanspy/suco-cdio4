<?php

namespace App\Services\Auth;

use App\Entities\Admin;
use App\Entities\User;
use App\Exceptions\Auth\AuthAttachRoleFailException;
use App\Exceptions\Auth\AuthRegisterEmailUniqueException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\LoginInvalidCredentialsException;
use App\Repositories\AdminRepository;
use App\Repositories\BaseRepository;
use App\Repositories\UserRepository;
use App\Services\ArchiveService;
use App\Services\DeviceService;
use App\Services\LikeService;
use App\Services\StorageService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    private $guard;

    /** @var AdminRepository */
    private $adminRepo;

    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->adminRepo = app(AdminRepository::class);

        $this->userRepo = app(UserRepository::class);
    }

    /**
     * @return WorkChatService
     */
    public function workChat()
    {
        return app(WorkChatService::class);
    }

    /**
     * @return DeviceService
     */
    public function device()
    {
        return app(DeviceService::class);
    }

    /**
     * @return LikeService
     */
    public function like()
    {
        return app(LikeService::class);
    }

    /**
     * @return ArchiveService
     */
    public function archive()
    {
        return app(ArchiveService::class);
    }

    /**
     * @return PasswordService
     */
    public function password()
    {
        return app(PasswordService::class);
    }

    /**
     * @return RoleService
     */
    public function role()
    {
        return app(RoleService::class);
    }

    /**
     * @return StorageService
     */
    public function storage()
    {
        return app(StorageService::class);
    }

    /**
     * @return mixed
     */
    private function auth()
    {
        if ($this->guard) {
            return Auth::guard($this->guard);
        }

        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin');
        }

        return Auth::guard('api');
    }

    /**
     * @return string
     */
    private function getGuard()
    {
        if ($this->guard) {
            return $this->guard;
        }

        if (Auth::guard('admin')->check()) {
            return 'admin';
        }

        return 'api';
    }

    /**
     * Get current auth repository by guard
     *
     * @return BaseRepository
     */
    public function repo()
    {
        if ($this->getGuard() == 'admin') {
            return $this->adminRepo;
        }

        return $this->userRepo;
    }

    /**
     * get current authenticated user info
     *
     * @return Admin|User
     */
    public function user()
    {
        $user = $this->auth()->user();

        $user->roles;

        return $user;
    }

    /**
     * @param $credentials
     * @return array
     * @throws InvalidCredentialsException
     */
    public function attempt($credentials)
    {
        if (! $token = $this->auth()->attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        return $this->responseToken($token);
    }

    /**
     * @param $user
     * @return array
     * @throws InvalidCredentialsException
     */
    public function attemptByModel($user)
    {
        if (! $token = $this->auth()->login($user)) {
            throw new InvalidCredentialsException();
        }

        return $this->responseToken($token);
    }

    /**
     * @param $token
     * @return array
     */
    private function responseToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expired_in' => $this->auth()->factory()->getTTL() * config('jwt.ttl')
        ];

        return $data;
    }

    /**
     * get current authenticated user id
     */
    public function id()
    {
        return $this->auth()->id();
    }

    /**
     * @param $guard
     * @return  AuthService
     */
    public function guard($guard)
    {
        $this->guard = $guard;

        return $this;
    }

    /**
     * Check if logged in or not
     *
     * @return mixed
     */
    public function check()
    {
        return $this->auth()->check();
    }

    /**
     * Login user and  return an access token
     *
     * @param $credentials
     * @return array
     * @throws LoginInvalidCredentialsException
     */
    public function login($credentials)
    {
        try {
            return $this->attempt($credentials);
        } catch (InvalidCredentialsException $e) {
            throw new LoginInvalidCredentialsException();
        }
    }

    /**
     * @param $data
     * @return array
     * @throws AuthAttachRoleFailException
     * @throws InvalidCredentialsException
     * @throws AuthRegisterEmailUniqueException
     * @throws Exception
     */
    public function register($data)
    {
        // validate unique email
        if ($this->repo()->where(['email' => $data['email'], 'provider' => null])->first()) {
            throw new AuthRegisterEmailUniqueException();
        }

        /** Store avatar */
        $data['avatar'] = $this->storage()->storeAvatar($data['avatar']);

        /** store new record */
        $data['password'] = Hash::make($data['password']);

        $auth = $this->repo()->create($data);

        /** login action */
        $responseToken = $this->attemptByModel($auth);

        /** attach role if user has role */
        if ($this->user()->hasRole) {
            $roleName = $data['role'] ?: $this->user()->defaultRole;

            $this->role()->attach($this->role()->id($roleName));
        }

        return $responseToken;
    }

    /**
     * @return boolean
     */
    public function logout()
    {
        $this->auth()->logout();

        return true;
    }

    /**
     * @param $data
     * @return Authenticatable|null
     */
    public function updateProfile($data)
    {
        if (isset($data['email'])) {
            unset($data['email']);
        }

        if (isset($data['avatar'])) {
            unset($data['avatar']);
        }

        $this->repo()->update($this->id(), $data);

        return $this->repo()->find($this->id());
    }

    /**
     * @param $avatar
     * @return string
     */
    public function updateAvatar($avatar)
    {
        $avatarUrl = $this->storage()->storeAvatar($avatar);

        $this->storage()->deleteAvatar($this->auth()->user()->avatar);

        $this->repo()->update($this->id(), ['avatar' => $avatarUrl]);

        return $avatarUrl;
    }
}
