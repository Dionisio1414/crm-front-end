<?php

namespace App\Services\Service;

use App\Core\Traits\CoreService;
use App\Core\Traits\ConsumeExternalService;

class ProductService {

    use ConsumeExternalService, CoreService;

    public function getProducts(){
        return $this->performRequestQuery('GET','api/v1/products', $this->request);
    }
}
