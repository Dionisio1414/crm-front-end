<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;

class UserService {

    use ConsumeExternalService;

    protected $baseUri;
    protected $secret;

    public function __construct()
    {
        $this->baseUri = config('services.gateway.base_uri');
        $this->secret  = config('services.gateway.secret');
    }

    public function registerCompany($data=[]){
        return json_decode($this->performRequestForm('POST','api/v1/user/register', $data));
    }

    public function updateCompany($id, $data=[]){
        return json_decode($this->performRequestForm('PUT','api/v1/admin/user/company/' . $id, $data));
    }

    public function deleteCompany($id){
        return json_decode($this->performDelete('api/v1/admin/user/company/' . $id));
    }
}
