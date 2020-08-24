<?php

namespace Iys;

abstract class AbstractEndpoint
{
    /** @var $httpClient HttpClient */
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

}
