<?php

namespace App\Core\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

trait ConsumeExternalService
{
    //GET
    public function performRequestQuery($method , $requestUrl, $queryParams=[], $headers=[])
    {
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if(isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        $response = $client->request($method, $requestUrl, [
            RequestOptions::QUERY       => $queryParams,
            RequestOptions::HEADERS     => $headers
        ]);
        return $response->getBody()->getContents();
    }

    //POST
    public function performRequestForm($method , $requestUrl, $formParams=[], $headers=[])
    {
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if(isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        $response = $client->request($method, $requestUrl, [
            RequestOptions::JSON        => $formParams,
            RequestOptions::HEADERS     => $headers,
            RequestOptions::HTTP_ERRORS => false
        ]);

        return $response->getBody()->getContents();
    }
}
