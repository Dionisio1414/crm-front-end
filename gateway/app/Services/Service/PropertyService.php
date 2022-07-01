<?php

namespace App\Services\Service;

use App\Core\Traits\CoreService;
use App\Core\Traits\ConsumeExternalService;

class PropertyService {

    use ConsumeExternalService, CoreService;

    public function getProperties()
    {
        return $this->performRequestQuery('GET','api/v1/products/properties', $this->request);
    }

    public function storeProperty()
    {
        return $this->performRequestForm('POST','api/v1/products/properties', $this->request);
    }

    public function editProperty($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/properties/' . $id, $this->request);
    }

    public function sortProperties()
    {
        return $this->performRequestForm('POST','api/v1/products/properties/sortProperties', $this->request);
    }

    public function getProperty($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/products/properties/' . $id . '/edit', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST','api/v1/products/properties/toArchive', $this->request);
    }

    public function getPropertiesCategory($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/properties/' . $id . '/properties-category', $this->request);
    }

    public function addPropertyValue($id)
    {
        return $this->performRequestForm('POST','api/v1/products/properties/' . $id . '/add-property-value', $this->request);
    }

    public function editPropertyValue($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/properties/' . $id . '/edit-property-value', $this->request);
    }
}
