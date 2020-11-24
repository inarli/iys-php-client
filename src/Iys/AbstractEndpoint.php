<?php

namespace Iys;

use Utils\Console;

abstract class AbstractEndpoint
{
    /** @var $httpClient HttpClient */
    protected $httpClient;
    /**
     * @var Console
     */
    protected $console;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
        $this->console = new Console();
    }

}
