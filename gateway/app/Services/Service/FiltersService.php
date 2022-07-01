<?php

namespace App\Services\Service;

use App\Core\Traits\ApiResponder;
use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;
use Illuminate\Http\Response;
use App\Core\Helpers\Directory\CheckClassSeedsDirectory;
use Illuminate\Support\Facades\Artisan;

class FiltersService
{
    use ConsumeExternalService, CoreService, ApiResponder;

    public function get($filter)
    {
        return $this->performRequestQuery('GET', 'api/v1/filters/' . $filter, $this->request);
    }

    public function store($filter)
    {
        return $this->performRequestForm('POST', 'api/v1/filters/' . $filter , $this->request);
    }
}
