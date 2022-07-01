<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class FileManagerService
{
    use ConsumeExternalService, CoreService;

    public function createFile($data=[])
    {
        $this->request = array_merge($data, $this->request);
        return $this->performRequestForm('POST', 'api/v1/file_manager/file', $this->request);
    }

    public function getListFiles()
    {
        return $this->performRequestQuery('GET', 'api/v1/file_manager/list', $this->request);
    }

    public function getFile($id)
    {
        return $this->performRequestQuery('GET', 'api/v1/file_manager/file/' . $id, $this->request);
    }

    public function delete()
    {
        return $this->performRequestForm('POST', 'api/v1/file_manager/delete', $this->request);
    }
}
