<?php

namespace App\Services\Auth;

use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\LoginInvalidCredentialsException;
use Exception;
use GuzzleHttp\Client;

class WorkChatService
{
    /**
     * @return AuthService
     */
    private function auth()
    {
        return app(AuthService::class);
    }

    /**
     * Login user and  return an access token
     *
     * @param $data
     * @return array
     * @throws InvalidCredentialsException
     * @throws LoginInvalidCredentialsException
     */
    public function login($data)
    {
        $credentials = $this->sendLoginRequestToWorkChat($data);

        // login if user exist
        if ($user = $this->checkCredentialsWorkChat($credentials)) {
            return $this->auth()->attemptByModel($user);
        }

        // create new user if user does not exist
        $user = $this->auth()->repo()->create($credentials);

        // attempt user
        return $this->auth()->attemptByModel($user);
    }

    /**
     * @param $data
     * @return array
     * @throws LoginInvalidCredentialsException
     */
    private function sendLoginRequestToWorkChat($data)
    {
        $client = new Client([
            'base_uri' => config('auth.wc_url')
        ]);

        try {
            $response = $client->post('/api/v1/login', [ 'json' => $data ]);

            $respondedData = json_decode($response->getBody()->getContents())->data;
            $user = $respondedData->me;

            $credentials = [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => config('auth.wc_url').'/avatar/'.$user->username,
                'password' => null,
                'provider' => config('auth.login_providers.work_chat'),
                'provider_id' => $respondedData->userId
            ];

            return $credentials;
        } catch (Exception $e) {
            throw new LoginInvalidCredentialsException();
        }
    }

    /**
     * @param $data
     * @return bool
     * @throws Exception
     * @throws InvalidCredentialsException
     */
    private function checkCredentialsWorkChat($data)
    {
        if ($this->checkEmailWorkChat($data['email'])) {
            if ($user = $this->checkProviderWorkChat($data)) {
                return $user;
            }

            throw new InvalidCredentialsException();
        }

        return false;
    }

    /**
     * @param $email
     * @return mixed
     * @throws Exception
     */
    private function checkEmailWorkChat($email)
    {
        return $this->auth()->repo()
            ->where([
                'email' => $email,
                'provider' => config('auth.login_providers.work_chat')
            ])
            ->first();
    }

    /**
     * @param $data
     * @return mixed
     * @throws Exception
     */
    private function checkProviderWorkChat($data)
    {
        return $this->auth()->repo()
            ->where([
                'email' => $data['email'],
                'provider' => config('auth.login_providers.work_chat'),
                'provider_id' => $data['provider_id']
            ])
            ->first();
    }
}
