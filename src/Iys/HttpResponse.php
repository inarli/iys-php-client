<?php

namespace Iys;

class HttpResponse
{
    private $statusCode;

    private $errors;

    private $errorCode;

    private $message;

    private $data;

    public function __construct($response){
        $this->buildResponse($response);
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $response
     * @return $this
     */
    private function buildResponse($response)
    {
        if ($response['info']['http_code']) {
            $this->statusCode = (int) $response['info']['http_code'];
        }
        if ($this->isClientError() || $this->isServerError()){
            if (isset($response['response']['errors'])){
                $this->errors = $response['response']['errors'];
            }
            if (isset($response['response']['message'])){
                $this->message = $response['response']['message'];
            }
            if (isset($response['response']['code'])){
                $this->errorCode = $response['response']['code'];
            }
        }
        if ($this->isSuccessful() && !empty($response['response'])) {
            $this->data = $response['response'];
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * @return bool
     */
    public function isClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * @return bool
     */
    public function isAuthError(){
        return $this->statusCode === 401;
    }

    /**
     * @return bool
     */
    public function isServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * @return array
     */
    public function toArray(){
        return [
            'statusCode' => $this->statusCode,
            'errors' => $this->errors,
            'errorCode' => $this->errorCode,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
