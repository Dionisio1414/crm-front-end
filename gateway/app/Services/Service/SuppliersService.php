<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;
use Illuminate\Support\Facades\Artisan;

class SuppliersService
{
    use ConsumeExternalService, CoreService;

    public function getTable()
    {
        return $this->performRequestQuery('GET', 'api/v1/suppliers/table', $this->request);
    }

    public function createItems()
    {
        return $this->performRequestQuery('POST', 'api/v1/suppliers/list', $this->request);
    }

    public function editItems($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/suppliers/list/' . $id, $this->request);
    }

    public function updateHeader()
    {
        return $this->performRequestForm('PUT', 'api/v1/suppliers/headers', $this->request);
    }


    public function toArchive()
    {
        return $this->performRequestForm('POST', 'api/v1/suppliers/toArchive', $this->request);
    }

    public function storeAsyncValidations()
    {
        return $this->performRequestQuery('GET','api/v1/suppliers/store-async-validations', $this->request);
    }

    public function updateAsyncValidations()
    {
        return $this->performRequestQuery('GET','api/v1/suppliers/update-async-validations', $this->request);
    }

    public function getList()
    {
        return $this->performRequestQuery('GET', 'api/v1/suppliers/list', $this->request);
    }

    public function changeGroups()
    {
        return $this->performRequestForm('POST', 'api/v1/suppliers/changeGroups', $this->request);
    }

    public function defaultHeader()
    {
        $class = 'SuppliersHeaders';

        Artisan::call('database:company_seeds_default',[
            'class'    => $class,
            'database' => $this->request['db'],
        ]);

        return 'Successfully';
    }
}
