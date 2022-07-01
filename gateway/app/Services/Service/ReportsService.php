<?php

namespace App\Services\Service;

use App\Core\Traits\ApiResponder;
use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreServiceCrm;

class ReportsService
{
    use ConsumeExternalService, CoreServiceCrm, ApiResponder;

    public function get($report)
    {
        return $this->performRequestQuery('GET', 'api/v1/reports/' . $report, $this->request);
    }

}
