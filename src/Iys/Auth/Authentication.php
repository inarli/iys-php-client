<?php
namespace Iys\Auth;

use Iys\AbstractEndpoint;
use Iys\Auth\Response\OauthToken;
use Iys\Auth\Response\RefreshToken;
use Iys\Auth\Response\Token;

class Authentication extends AbstractEndpoint
{
    private $endpoint = 'oauth/token';
    private $endpointOauth2 = 'oauth2/token';
    private $endpointRefresh = 'oauth/refresh';

    /**
     * @param $email
     * @param $password
     * @return false|Token
     * @throws \Exception
     */
    public function login($email, $password){
        $data = [
            'username' => $email,
            'password' => $password,
        ];

        $response = $this->httpClient->auth($this->endpoint, $data);
        if (!$response->isSuccessful()){
            return false;
        }
        $token = new Token();
        $token->setMessage($response->getData()['message']);
        $token->setStatus($response->getData()['status']);
        $token->setAccessToken($response->getData()['result']['accessToken']);
        $token->setRefreshToken($response->getData()['result']['refreshToken']);
        $token->setExpiresIn($response->getData()['result']['expiresIn']);
        $token->setTokenType($response->getData()['result']['tokenType']);
        return $token;
    }

    /**
     * @param $email
     * @param $password
     * @return OauthToken|false
     * @throws \Exception
     */
    public function loginWithOauth2($email, $password) {
        $data = [
            'username' => $email,
            'password' => $password,
            'grant_type' => 'password'
        ];
        $response = $this->httpClient->auth2($this->endpointOauth2, $data);
        if (!$response){
            return false;
        }
        $oauth2Token = new OauthToken();
        $oauth2Token->setAccessToken($response->getData()['access_token']);
        $oauth2Token->setRefreshToken($response->getData()['refresh_token']);
        $oauth2Token->setExpiresIn($response->getData()['expires_in']);
        $oauth2Token->setTokenType($response->getData()['token_type']);
        $oauth2Token->setRefreshExpiresIn($response->getData()['refresh_expires_in']);
        return $oauth2Token;
    }

    /**
     * @param $refreshToken
     * @return RefreshToken|false
     * @throws \Exception
     */
    public function refreshToken($refreshToken = null){
        $response = $this->httpClient->refreshToken($this->endpointRefresh, $refreshToken);
        if (!$response->isSuccessful()){
            return false;
        }
        $refreshTokenObj = new RefreshToken();
        $refreshTokenObj->setRefreshToken($response->getData()['result']['refreshToken']);
        $refreshTokenObj->setMessage($response->getData()['message']);
        $refreshTokenObj->setTokenType($response->getData()['result']['tokenType']);
        $refreshTokenObj->setExpiresIn($response->getData()['result']['expiresIn']);
        $refreshTokenObj->setAccessToken($response->getData()['result']['accessToken']);
        return $refreshTokenObj;
    }

}
