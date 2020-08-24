<?php

namespace Iys;

use Iys\Auth\Authentication;
use Iys\ConsentManagement\ConsentManagement;

class IysApi
{
    /** @var HttpClient */
    protected $httpClient;

    /** @var ConsentManagement $consentManagement */
    public $consentManagement;

    /**
     * @var Authentication
     */
    public $authentication;

    public function __construct($apiUri, $iysCode, $brandCode){

        $this->httpClient = $this->buildHttpClient($apiUri, $iysCode, $brandCode);
        $this->authentication = new Authentication($this->httpClient);
        $this->consentManagement = new ConsentManagement($this->httpClient);
    }

    /**
     * @param $apiUri
     * @param $iysCode
     * @param $brandCode
     * @return HttpClient
     */
    private function buildHttpClient($apiUri, $iysCode, $brandCode){
        return new HttpClient($apiUri, $iysCode, $brandCode);
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->httpClient->setToken($token);
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->httpClient->setRefreshToken($refreshToken);
    }
}
