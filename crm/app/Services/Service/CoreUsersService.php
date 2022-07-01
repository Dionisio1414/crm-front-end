<?php

namespace App\Services\Service;

use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;

class CoreUsersService
{
    use ConsumeExternalService, CoreService;

    public function createGatewayUser($data)
    {
        return json_decode($this->performRequestForm('POST',
            'api/v1/admin/user/createRegular',
            $data
        ));
    }

    public function updateGatewayUser($id, $data)
    {
        return json_decode($this->performRequestForm('PUT',
            'api/v1/admin/user/updateRegular/' . $id,
            $data
        ));
    }
}
