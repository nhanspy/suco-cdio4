<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\Auth\AuthArchiveException;
use App\Exceptions\Auth\AuthAttachRoleFailException;
use App\Exceptions\Auth\AuthChangePasswordException;
use App\Exceptions\Auth\AuthDeleteArchiveException;
use App\Exceptions\Auth\AuthDeleteLikeException;
use App\Exceptions\Auth\AuthLikeException;
use App\Exceptions\Auth\AuthNotFoundException;
use App\Exceptions\Auth\AuthRegisterEmailUniqueException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\LoginInvalidCredentialsException;
use App\Http\Requests\Auth\AuthChangePasswordRequest;
use App\Http\Requests\Auth\AuthLoginWithWorkChatRequest;
use App\Http\Requests\Auth\AuthSendResetPasswordEmailRequest;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthResetPasswordRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Requests\Auth\AuthUpdateAvatarRequest;
use App\Http\Requests\Auth\AuthUpdateProfileRequest;
use App\Exceptions\Auth\InvalidResetPasswordTokenException;
use App\Exceptions\Auth\ResetPasswordTokenExpiredException;
use App\Http\Requests\User\UserGetArchivesRequest;
use App\Services\Auth\AuthService;
use App\Exceptions\Auth\CanResetPasswordException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;

class AuthController extends Controller
{
    /** @var AuthService */
    private $auth;

    public function __construct()
    {
        $this->auth = app(AuthService::class);
    }

    /**
     * @param AuthLoginRequest $request
     * @return JsonResponse
     * @throws LoginInvalidCredentialsException
     * @throws Exception
     */
    public function login(AuthLoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $credentials['provider'] = null;

        $data = $this->auth->login($credentials);

        $this->auth->archive()->moveArchivesFromDeviceToAuthAfterLogin();

        return $this->response('auth.login.success', $data);
    }

    /**
     * @param AuthLoginWithWorkChatRequest $request
     * @return JsonResponse
     * @throws LoginInvalidCredentialsException*@throws Exception
     * @throws InvalidCredentialsException
     * @throws Exception
     */
    public function loginWithWorkChat(AuthLoginWithWorkChatRequest $request)
    {
        $data = $request->only(['email', 'password']);

        $response = $this->auth->workChat()->login($data);

        $this->auth->archive()->moveArchivesFromDeviceToAuthAfterLogin();

        return $this->response('auth.login.success', $response);
    }

    /**
     * @param AuthRegisterRequest $request
     * @return JsonResponse
     * @throws AuthAttachRoleFailException
     * @throws InvalidCredentialsException
     * @throws AuthRegisterEmailUniqueException
     */
    public function register(AuthRegisterRequest $request)
    {
        $data = $request->all();
        $data['avatar'] = $request->file('avatar');
        $auth = $this->auth->register($data);

        return $this->response('auth.register.success', $auth);
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $this->auth->logout();

        return $this->response('auth.logout.success');
    }

    /**
     * @return JsonResponse
     */
    public function validateToken()
    {
        return $this->response('auth.token.validate_success');
    }

    /**
     * @param AuthSendResetPasswordEmailRequest $request
     * @return JsonResponse
     * @throws CanResetPasswordException
     * @throws AuthNotFoundException
     */
    public function sendResetPasswordEmail(AuthSendResetPasswordEmailRequest $request)
    {
        $this->auth->password()->sendResetPasswordEmail($request->get('email'), true);

        return $this->response('auth.reset_password.send_email_success');
    }

    /**
     * @param AuthResetPasswordRequest $request
     * @return JsonResponse
     * @throws CanResetPasswordException
     * @throws InvalidResetPasswordTokenException
     * @throws ResetPasswordTokenExpiredException
     * @throws AuthNotFoundException
     * @throws InvalidCredentialsException
     */
    public function resetPassword(AuthResetPasswordRequest $request)
    {
        $data = $request->only(['email', 'token', 'password']);

        $response = $this->auth->password()->reset($data);

        return $this->response('auth.reset_password.success', $response);
    }

    /**
     * @param AuthChangePasswordRequest $request
     * @return JsonResponse
     * @throws InvalidCredentialsException
     * @throws AuthChangePasswordException
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
        $data = $this->auth->user();

        return $this->response('auth.profile.get_profile_success', $data);
    }

    /**
     * @param AuthUpdateProfileRequest $request
     * @return JsonResponse
     */
    public function updateProfile(AuthUpdateProfileRequest $request)
    {
        $data = $request->only(['name', 'language', 'position']);

        $profile = $this->auth->updateProfile($data);

        return $this->response('auth.profile.update_profile_success', $profile);
    }

    /**
     * @param AuthUpdateAvatarRequest $request
     * @return JsonResponse
     */
    public function updateAvatar(AuthUpdateAvatarRequest $request)
    {
        $avatar = $this->auth->updateAvatar($request->file('avatar'));

        return $this->response('auth.profile.update_avatar_success', ['avatar' => $avatar]);
    }

    /**
     * @param $token
     * @param $email
     * @return RedirectResponse
     */
    public function redirectResetPasswordLink($token, $email)
    {
        return redirect()->to(config('auth.mobile_url').'/auth/password/reset/'.$token.'/email/'.$email);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthLikeException
     */
    public function like($id)
    {
        $totalLike = $this->auth->like()->create($id);

        return $this->response('auth.like.success', [ 'total_like' => $totalLike]);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthDeleteLikeException
     */
    public function unLike($id)
    {
        $totalLike = $this->auth->like()->delete($id);

        return $this->response('auth.like.delete.success', ['total_like' => $totalLike]);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthArchiveException
     */
    public function archive($id)
    {
        $totalArchives = $this->auth->archive()->create($id);

        return $this->response('auth.archive.success', ['total_archives' => $totalArchives]);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws AuthDeleteArchiveException
     */
    public function unArchive($id)
    {
        $totalArchives = $this->auth->archive()->delete($id);

        return $this->response('auth.archive.delete.success', ['total_archives' => $totalArchives]);
    }

    /**
     * Get all translations has archived by user or devices
     *
     * @param UserGetArchivesRequest $request
     * @return JsonResponse
     */
    public function archives(UserGetArchivesRequest $request)
    {
        $archives = $this->auth->archive()->all($request->get('perPage'));

        return $this->response('auth.get_archives.success', $archives);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function countArchives()
    {
        $count = $this->auth->archive()->count();

        return $this->response('auth.count_archives.success', ['total' => $count]);
    }
}
