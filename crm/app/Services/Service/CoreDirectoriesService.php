<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class CoreDirectoriesService
{
    use ConsumeExternalService, CoreService;

    public function getList($directory)
    {
        return $this->performRequestQuery('GET',
            'api/v1/admin/directories/core/' . $directory . '/list'
        );
    }

    public function getById($directory, $id)
    {
        return $this->performRequestQuery('GET',
            'api/v1/admin/directories/core/' . $directory . '/list/' . $id
        );
    }

    public function getLanguages()
    {
        return json_decode($this->performRequestQuery('GET',
            'api/v1/admin/directories/languages'
        ));
    }
}
