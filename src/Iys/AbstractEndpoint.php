<?php

namespace Iys;

use Shalvah\Clara\Clara;

abstract class AbstractEndpoint
{
    /** @var $httpClient HttpClient */
    protected $httpClient;
    /**
     * @var Clara
     */
    protected $console;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
        $this->console = new Clara('iys');
    }

}
