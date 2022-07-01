<?php

namespace App\Services\Service;

use App\Core\Traits\CoreService;
use App\Core\Traits\ConsumeExternalService;

class WarehouseService {

    use ConsumeExternalService, CoreService;

    public function getWarehouses()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses', $this->request);
    }

    public function storeWarehouse()
    {
        return $this->performRequestForm('POST','api/v1/products/warehouses', $this->request);
    }

    public function storeWarehouseGroup()
    {
        return $this->performRequestForm('POST','api/v1/products/warehouses/create-warehouse-group', $this->request);
    }

    public function editWarehouse($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/warehouses/' . $id, $this->request);
    }

    public function updateWarehouseGroup($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/warehouses/update-warehouse-group/' . $id, $this->request);
    }

    public function showWarehouse($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/'. $id, $this->request);
    }

    public function showWarehouseGroup($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/get-warehouse-group/'. $id, $this->request);
    }

    public function showProducts($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/products/'. $id, $this->request);
    }

    public function showProductsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/products-all', $this->request);
    }

    public function showKits($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/kits/'. $id, $this->request);
    }

    public function showKitsAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/kits-all', $this->request);
    }

    public function getWarehousesAll()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/get-warehouses', $this->request);
    }

    public function getWarehousesGroups()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/get-warehouses-groups', $this->request);
    }

    public function toArchiveWarehouse()
    {
        return $this->performRequestForm('POST','api/v1/products/warehouses/toArchiveWarehouse', $this->request);
    }

    public function toArchiveWarehouseGroup()
    {
        return $this->performRequestForm('POST','api/v1/products/warehouses/toArchiveWarehouseGroup', $this->request);
    }

    public function moveWarehouses($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/warehouses/move-warehouses/' . $id, $this->request);
    }

    public function fillProductsStocks($id)
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/fill-products-stocks/'. $id, $this->request);
    }

    public function chooseDefaultWarehouse($id)
    {
        return $this->performRequestForm('PUT','api/v1/products/warehouses/choose-default-warehouse/' . $id, $this->request);
    }

    public function getDefaultWarehouse()
    {
        return $this->performRequestQuery('GET','api/v1/products/warehouses/get-default-warehouse', $this->request);
    }
}
