<?php

namespace App\Services\Auth;

use App\Entities\User;
use App\Exceptions\Auth\AuthChangePasswordException;
use App\Exceptions\Auth\AuthNotFoundException;
use App\Exceptions\Auth\CanResetPasswordException;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\InvalidResetPasswordTokenException;
use App\Exceptions\Auth\ResetPasswordTokenExpiredException;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordService
{
    private $config;

    public function __construct()
    {
        $this->config = config('auth.reset_password');
    }

    /**
     * @return AuthService
     */
    private function auth()
    {
        return app(AuthService::class);
    }

    /**
     * @param $data
     * @param $admin
     * @return array
     * @throws InvalidResetPasswordTokenException
     * @thorws InvalidCredentialsException
     * @throws CanResetPasswordException
     * @throws ResetPasswordTokenExpiredException
     * @throws AuthNotFoundException
     * @throws InvalidCredentialsException
     */
    public function reset($data, $admin = false)
    {
        // validate token : exist and time expired
        $this->validateToken($data);

        // validate auth
        $auth = $this->checkEmail($data['email'], $admin);

        // validate if not an instance of CanResetPassword
        if (! $auth instanceof CanResetPassword) {
            throw new CanResetPasswordException();
        }

        // resetPassword
        $auth->password = Hash::make($data['password']);

        $auth->save();

        // delete Token
        $this->deleteToken($data['email']);

        // login and response token
        return $this->auth()->attemptByModel($auth);
    }

    /**
     * @param $data
     * @return array
     * @throws AuthChangePasswordException
     * @throws InvalidCredentialsException
     */
    public function change($data)
    {
        $email = $this->auth()->user()->email;

        $credentials = ['email' => $email, 'password' => $data['password']];

        try {
            $this->auth()->attempt($credentials);
        } catch (InvalidCredentialsException $e) {
            throw new AuthChangePasswordException();
        }

        $this->auth()->user()->update([
            'email' => $email,
            'password' => Hash::make($data['new_password'])
        ]);

        return $this->auth()->attempt([
            'email' => $email,
            'password' => $data['new_password']
        ]);
    }

    /**
     * @param $email
     * @param bool $mobile
     * @param bool $admin
     * @return bool
     * @throws AuthNotFoundException
     * @throws CanResetPasswordException
     */
    public function sendResetPasswordEmail($email, $mobile = false, $admin = false)
    {
        // check if have a auth with provided credential
        $auth = $this->checkEmail($email, $admin);

        // validate if not an instance of CanResetPassword
        if (! $auth instanceof CanResetPassword) {
            throw new CanResetPasswordException();
        }

        // create token
        // store email and token to database
        // send email to auth email
        $this->sendResetLink($auth, $mobile);

        return true;
    }

    /**
     * @param CanResetPassword|User $auth
     * @param bool $mobile
     */
    private function sendResetLink(CanResetPassword $auth, $mobile = false)
    {
        if ($mobile) {
            $auth->sendMobilePasswordResetNotification($this->createToken($auth));
        } else {
            $auth->sendPasswordResetNotification($this->createToken($auth));
        }
    }

    /**
     * @param $email
     * @param $admin
     * @return Collection
     * @throws AuthNotFoundException
     * @throws Exception
     */
    private function checkEmail($email, $admin = false)
    {
        $auth = $admin
            ? $this->auth()->guard('admin')->repo()->where(['email' => $email])->first()
            : $this->auth()->repo()->where(['email' => $email, 'provider' => null])->first();

        if ($auth) {
            return $auth;
        }

        throw new AuthNotFoundException();
    }

    /**
     * @param CanResetPassword $auth
     * @return string
     */
    private function createToken(CanResetPassword $auth)
    {
        $email = $auth->getEmailForPasswordReset();

        $this->deleteToken($email);

        $token = hash_hmac('sha256', Str::random(40), config('app.key'));

        $this->saveTokenToDatabase($email, $token);

        return $token;
    }

    /**
     * @param $email
     * @param $token
     */
    private function saveTokenToDatabase($email, $token)
    {
        DB::table($this->config['table'])->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now()
        ]);
    }

    /**
     * check if token exist
     * check if token is expired
     * @param $data
     * @throws InvalidResetPasswordTokenException
     * @throws ResetPasswordTokenExpiredException
     */
    private function validateToken($data)
    {
        $record = $this->isTokenExist($data);

        // token expired
        $tokenExpireAt = Carbon::createFromFormat('Y-m-d H:i:s', $record->created_at)
            ->addMinutes($this->config['expire']);

        if ($tokenExpireAt < Carbon::now()) {
            $this->deleteToken($data['email']);

            throw new ResetPasswordTokenExpiredException();
        }
    }

    /**
     * @param $data
     * @return Model|Builder|object|null
     * @throws InvalidResetPasswordTokenException
     */
    private function isTokenExist($data)
    {
        // token exist
        $record = DB::table($this->config['table'])
            ->where('token', $data['token'])
            ->where('email', $data['email'])
            ->first();

        if (is_null($record)) {
            throw new InvalidResetPasswordTokenException();
        }

        return $record;
    }

    /**
     * Delete token
     * @param $email
     */
    private function deleteToken($email)
    {
        DB::table($this->config['table'])->where('email', $email)->delete();
    }
}
