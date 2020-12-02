<?php

namespace Iys;

use Iys\Auth\Exceptions\AuthException;

class HttpClient
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * @var array
     */
    private $requestInfo;

    /**
     * @var string
     */
    private $serviceUrl;


    /**
     * HttpClient constructor.
     * @param $baseUrl
     * @param $iysCode
     * @param $iysBrandCode
     */
    public function __construct($baseUrl, $iysCode, $iysBrandCode){
        $this->baseUrl = $baseUrl;
        $this->serviceUrl = sprintf('%s/sps/%s/brands/%s', $baseUrl, $iysCode, $iysBrandCode);
    }

    /**
     * @param null $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @param $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return mixed
     */
    public function getHeader(){

        $header[] = 'Content-Type: application/json';
        if ($this->token !== null){
            $header[] = 'Authorization: Bearer '.$this->token;
        }
        return $header;
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return HttpResponse
     * @throws \Exception
     */
    public function auth($endpoint, $payload){
        $this->request($this->baseUrl.'/'.$endpoint, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $this->preparePayload($payload),
            CURLOPT_VERBOSE => false,
        ]);
        return $this->handleResponse();
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return HttpResponse
     * @throws \Exception
     */
    public function post($endpoint, $payload)
    {
        $url = $this->serviceUrl.$endpoint;
        $this->request($url, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->getHeader(),
            CURLOPT_POSTFIELDS => $this->preparePayload($payload),
            CURLOPT_VERBOSE => false,
        ]);
        return $this->handleResponse();
    }

    /**
     * @param $endpoint
     * @return HttpResponse
     * @throws \Exception
     */
    public function get($endpoint)
    {
        $url = $this->serviceUrl.$endpoint;
        $this->request($url, array(
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->getHeader(),
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => false,
        ));
        return $this->handleResponse();
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return HttpResponse
     * @throws \Exception
     */
    public function put($endpoint, $payload)
    {
        $url = $this->serviceUrl.$endpoint;

        $this->request($url, array(
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->getHeader(),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => false,
        ));
        return $this->handleResponse();
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return HttpResponse
     * @throws \Exception
     */
    public function delete($endpoint, $payload)
    {
        $url = $this->serviceUrl.$endpoint;

        $this->request($url, array(
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->getHeader(),
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => false,
        ));
        return $this->handleResponse();
    }

    /**
     * @param $url
     * @param $options
     * @return bool
     * @throws \Exception
     */
    private function request($url, $options){
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        if ($response === false){
            throw new \Exception('Http Client Exception. Reason:'.curl_error($ch));
        }

        $this->requestInfo['response'] = json_decode($response,true);
        $this->requestInfo['info'] = curl_getinfo($ch);
        return true;
    }

    /**
     * @return HttpResponse
     */
    private function handleResponse(){
        return new HttpResponse($this->requestInfo);
    }

    /**
     * @param $data
     * @return false|string
     */
    private function preparePayload($data){

        $filtered = [];
        foreach ($data as $item){
            if (is_array($item) && !empty($item)){
                $filtered[] = array_filter($item);
            }
        }
        $payload = !empty($filtered) ? $filtered : array_filter($data);
        return json_encode($payload);
    }

    /**
     * @param $endpoint
     * @param array $payload
     * @return HttpResponse
     * @throws \Exception
     */
    public function auth2($endpoint, array $payload)
    {
        $this->request($this->baseUrl.'/'.$endpoint, [
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_VERBOSE => false
        ]);
        return $this->handleResponse();
    }

    /**
     * @param string $endpoint
     * @param null|string $refreshToken
     * @return HttpResponse
     * @throws \Exception
     */
    public function refreshToken($endpoint, $refreshToken = null)
    {
        if (!$refreshToken) {
            $refreshToken = $this->refreshToken;
        }
        if(!$refreshToken) {
            throw new AuthException('No refresh token');
        }
        $payload = ['refreshToken' => $refreshToken];
        $this->auth($endpoint, $payload);
        return $this->handleResponse();
    }
}
