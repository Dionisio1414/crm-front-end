<?php

namespace App\Core\Traits;

trait CoreService
{
    protected $baseUri, $secret;

    public function __construct()
    {
        $this->baseUri = config('services.gateway.base_uri');
        $this->secret = config('services.gateway.secret');
    }
}
