<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class CharacteristicService
{

    use ConsumeExternalService, CoreService;

    public function getCharacteristics()
    {
        return $this->performRequestQuery('GET', 'api/v1/products/characteristics', $this->request);
    }

    public function createCharacteristic()
    {
        return $this->performRequestForm('POST', 'api/v1/products/characteristics', $this->request);
    }

    public function editCharacteristic($id)
    {

        return $this->performRequestForm('PUT', 'api/v1/products/characteristics/' . $id, $this->request);
    }

    public function editColorCharacteristic($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/products/characteristics/updateColorCharacteristics', $this->request);
    }

    public function editSizeCharacteristic($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/products/characteristics/updateSizeCharacteristics', $this->request);
    }

    public function sortCharacteristics()
    {
        return $this->performRequestForm('POST', 'api/v1/products/characteristics/sortCharacteristics', $this->request);
    }

    public function getCharacteristic($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/products/characteristics/' . $id . '/edit', $this->request);
    }

    public function getColorCharacteristic()
    {
        return $this->performRequestQuery('GET', 'api/v1/products/characteristics/editColorCharacteristics', $this->request);
    }

    public function getSizeCharacteristic()
    {
        return $this->performRequestQuery('GET', 'api/v1/products/characteristics/editSizeCharacteristics', $this->request);
    }

    public function getCharacteristicsCategory($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/products/characteristics/' . $id . '/characteristics-category', $this->request);
    }

    public function addCharacteristicValue($id)
    {
        return $this->performRequestForm('POST', 'api/v1/products/characteristics/' . $id . '/add-characteristic-value', $this->request);
    }

    public function addCharacteristicColorValue()
    {
        return $this->performRequestForm('POST', 'api/v1/products/characteristics/add-characteristic-color-value', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST','api/v1/products/characteristics/toArchive', $this->request);
    }
}
