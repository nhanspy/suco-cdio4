<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\Auth\AuthAttachRoleFailException;
use App\Exceptions\Auth\AuthChangePasswordException as AuthChangePasswordExceptionAlias;
use App\Exceptions\Auth\AuthDetachRoleFailException;
use App\Exceptions\Auth\AuthNotFoundException;
use App\Exceptions\Auth\CanResetPasswordException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidResetPasswordTokenException;
use App\Exceptions\Auth\LoginInvalidCredentialsException;
use App\Exceptions\Auth\ResetPasswordTokenExpiredException;
use App\Http\Requests\Admin\AdminLoginRequest;
use App\Http\Requests\Admin\AdminResetPasswordRequest;
use App\Http\Requests\Admin\AdminSendResetPasswordEmailRequest;
use App\Http\Requests\Admin\AdminUpdateAvatarRequest;
use App\Http\Requests\Admin\AdminUpdateProfileRequest;
use App\Http\Requests\Auth\AuthChangePasswordRequest;
use App\Repositories\AdminRepository;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use UnexpectedValueException;

class AdminController extends Controller
{
    /** @var AuthService */
    private $auth;

    private $guard;

    private $adminRepo;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->auth = app(AuthService::class);
        $this->guard = config('auth.guard.admin');
        $this->adminRepo = app(AdminRepository::class);
    }

    /**
     * @param AdminLoginRequest $request
     * @return mixed
     * @throws LoginInvalidCredentialsException
     */
    public function login(AdminLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $data = $this->auth->guard($this->guard)->login($credentials);

        return $this->response('admin.login.success', $data);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $this->auth->guard($this->guard)->logout();

        return $this->response('admin.logout.success');
    }

    /**
     * @return JsonResponse
     */
    public function validateToken()
    {
        return $this->response('admin.token.validate_success');
    }

    /**
     * @param AdminSendResetPasswordEmailRequest $request
     * @return JsonResponse
     * @throws CanResetPasswordException
     * @throws AuthNotFoundException
     */
    public function sendResetPasswordEmail(AdminSendResetPasswordEmailRequest $request)
    {
        $this->auth->password()->sendResetPasswordEmail($request->get('email'), false, true);

        return $this->response('admin.reset_password.send_email_success');
    }

    /**
     * @param AdminResetPasswordRequest $request
     * @return JsonResponse
     * @throws InvalidResetPasswordTokenException
     * @throws UnexpectedValueException
     * @thorws InvalidCredentialsException
     * @throws CanResetPasswordException
     * @throws ResetPasswordTokenExpiredException
     * @throws AuthNotFoundException
     * @throws InvalidCredentialsException
     */
    public function resetPassword(AdminResetPasswordRequest $request)
    {
        $data = $request->only(['email', 'token', 'password']);
        $response = $this->auth->password()->reset($data, true);

        return $this->response('admin.reset_password.success', $response);
    }

    /**
     * @param AuthChangePasswordRequest $request
     * @return JsonResponse
     * @throws InvalidCredentialsException
     * @throws AuthChangePasswordExceptionAlias
     */
    public function changePassword(AuthChangePasswordRequest $request)
    {
        $data = $request->only(['password', 'new_password']);

        $responseToken = $this->auth->password()->change($data);

        return $this->response('auth.change_password.success', $responseToken);
    }

    /**
     * @return JsonResponse
     */
    public function profile()
    {
        $profile = $this->auth->user();

        return $this->response('admin.profile.get_profile_success', ['profile' => $profile]);
    }

    /**
     * @param AdminUpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(AdminUpdateProfileRequest $request)
    {
        $data = $request->all();
        $profile = $this->auth->updateProfile($data);

        return $this->response('admin.profile.update_profile_success', ['profile' => $profile]);
    }

    /**
     * @param AdminUpdateAvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatar(AdminUpdateAvatarRequest $request)
    {
        $avatar = $this->auth->updateAvatar($request->file('avatar'));

        return $this->response('admin.profile.update_avatar_success', ['avatar' => $avatar]);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthAttachRoleFailException
     */
    public function attachRole($id)
    {
        $this->auth->role()->attach($id);

        return $this->response('admin.role.attach_success');
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthDetachRoleFailException
     */
    public function detachRole($id)
    {
        $this->auth->role()->detach($id);

        return $this->response('admin.role.detach_success');
    }
}
