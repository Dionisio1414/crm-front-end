<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait ConsumeExternalService
{
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
            'form_params' => $formParams,
            'headers'     => $headers
        ]);

        return $response->getBody()->getContents();
    }

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
            'query'       => $queryParams,
            'headers'     => $headers
        ]);
        return $response->getBody()->getContents();
    }

    //DELETE
    public function performDelete($requestUrl)
    {
        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if(isset($this->secret)) {
            $headers['Authorization'] = $this->secret;
        }

        $response = $client->delete($this->baseUri . $requestUrl, [
            'headers'     => $headers
        ]);

        return $response->getBody()->getContents();
    }
}
