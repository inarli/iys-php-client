<?php

namespace Iys\Auth\Response;

class OauthToken extends Token
{
    private $refreshExpiresIn;

    /**
     * @return mixed
     */
    public function getRefreshExpiresIn()
    {
        return $this->refreshExpiresIn;
    }

    /**
     * @param mixed $refreshExpiresIn
     * @return OauthToken
     */
    public function setRefreshExpiresIn($refreshExpiresIn)
    {
        $this->refreshExpiresIn = $refreshExpiresIn;
        return $this;
    }


    public function generateResponseObject($jsonData)
    {
        $data = json_decode($jsonData, true);
        return (new self())
            ->setAccessToken($data['access_token'])
            ->setRefreshToken($data['refresh_token'])
            ->setExpiresIn($data['expires_in'])
            ->setRefreshExpiresIn($data['refresh_expires_in'])
            ->setTokenType($data['token_type']);
    }


}
