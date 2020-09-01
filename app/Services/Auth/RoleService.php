<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\AuthAttachRoleFailException;
use App\Exceptions\Auth\AuthDetachRoleFailException;
use App\Repositories\RoleRepository;

class RoleService
{
    /** @var RoleRepository */
    private $roleRepo;

    public function __construct()
    {
        $this->roleRepo = app(RoleRepository::class);
    }

    /**
     * @return AuthService
     */
    private function auth()
    {
        return app(AuthService::class);
    }

    /**
     * @param $id
     * @return bool
     * @throws AuthAttachRoleFailException
     */
    public function attach($id)
    {
        if ($this->auth()->user()->roles->contain($id)) {
            throw new AuthAttachRoleFailException();
        }

        $this->auth()->user()->roles()->attach($id);

        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws AuthDetachRoleFailException
     */
    public function detach($id)
    {
        if (!$this->auth()->user()->roles->contain($id)) {
            throw new AuthDetachRoleFailException();
        }

        $this->auth()->user()->roles()->detach($id);

        return true;
    }

    /**
     * @param $roleName
     * @return mixed
     */
    public function id($roleName)
    {
        return $this->auth()
            ->user()
            ->roles()
            ->where('name', $roleName)
            ->first()->id;
    }
}
