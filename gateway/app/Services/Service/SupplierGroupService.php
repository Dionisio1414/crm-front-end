<?php

namespace App\Services\Service;

use App\Core\Traits\CoreService;
use App\Core\Traits\ConsumeExternalService;

class SupplierGroupService
{
    use ConsumeExternalService, CoreService;

    public function getGroups()
    {
        return $this->performRequestQuery('GET','api/v1/suppliers/groups', $this->request);
    }

    public function createGroup()
    {
        return $this->performRequestForm('POST','api/v1/suppliers/groups', $this->request);
    }

    public function updateGroup($id)
    {
        return $this->performRequestForm('PUT','api/v1/suppliers/groups/' . $id, $this->request);
    }

    public function toArchive()
    {
        return $this->performRequestForm('POST', 'api/v1/suppliers/groups/toArchive', $this->request);
    }
}
