<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class CategoryService
{

    use ConsumeExternalService, CoreService;

    public function getCategories()
    {
        return $this->performRequestQuery('GET', 'api/v1/products/categories', $this->request);
    }

    public function getCategory($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/products/categories/' . $id, $this->request);
    }

    public function getEditCategory($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/products/categories/' . $id . '/edit', $this->request);
    }

    public function createCategory()
    {
        return $this->performRequestQuery('GET', 'api/v1/products/categories/create', $this->request);
    }

    public function storeCategory()
    {
        return $this->performRequestForm('POST', 'api/v1/products/categories', $this->request);
    }

    public function editCategory($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/products/categories/' . $id, $this->request);
    }

    public function sortCategories()
    {
        return $this->performRequestForm('POST', 'api/v1/products/categories/sortCategories', $this->request);
    }

    public function sortItemCategories()
    {
        return $this->performRequestForm('POST', 'api/v1/products/categories/sortItemCategories', $this->request);
    }

    public function visibilityCategory($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/products/categories/' . $id . '/visibility', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST','api/v1/products/categories/toArchive', $this->request);
    }

    public function storeAsyncValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/categories/store-async-validations', $this->request);
    }

    public function updateAsyncValidations()
    {
        return $this->performRequestForm('POST','api/v1/products/categories/update-async-validations', $this->request);
    }
}
