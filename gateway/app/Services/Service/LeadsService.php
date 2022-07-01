<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;
use Illuminate\Support\Facades\Artisan;

class LeadsService
{
    use ConsumeExternalService, CoreService;

    public function getTable()
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/table', $this->request);
    }

    public function createItems()
    {
        return $this->performRequestForm('POST', 'api/v1/leads/list', $this->request);
    }

    public function editItems($id)
    {
        return $this->performRequestForm('PUT', 'api/v1/leads/list/' . $id, $this->request);
    }

    public function getFailureTable()
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/failure-table' , $this->request);
    }

    public function show($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/list/' . $id, $this->request);
    }

    public function edit($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/list/' . $id . '/edit', $this->request);
    }

    public function storeOrderAndCustomer($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/store-order-and-customer/' . $id, $this->request);
    }

    public function updateHeader()
    {
        return $this->performRequestForm('PUT', 'api/v1/leads/headers', $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST', 'api/v1/leads/toArchive', $this->request);
    }

    public function toFailure()
    {
        return $this->performRequestForm('POST', 'api/v1/leads/toFailure', $this->request);
    }

    public function outFailure()
    {
        return $this->performRequestForm('POST', 'api/v1/leads/outFailure', $this->request);
    }

    public function storeAsyncValidations()
    {
        return $this->performRequestQuery('GET','api/v1/leads/store-async-validations', $this->request);
    }

    public function updateAsyncValidations()
    {
        return $this->performRequestQuery('GET','api/v1/leads/update-async-validations', $this->request);
    }

    public function getList()
    {
        return $this->performRequestQuery('GET', 'api/v1/leads/list', $this->request);
    }


    public function defaultHeader()
    {
        $class = 'LeadsHeaders';

        Artisan::call('database:company_seeds_default',[
            'class'    => $class,
            'database' => $this->request['db'],
        ]);

        return 'Successfully';
    }
}
