<?php

namespace App\Services\Service;

use App\Core\Traits\ApiResponder;
use App\Core\Traits\ConsumeExternalService;
use App\Core\Traits\CoreService;
use Illuminate\Http\Response;
use App\Core\Helpers\Directory\CheckClassSeedsDirectory;
use Illuminate\Support\Facades\Artisan;

class DirectoriesService
{
    use ConsumeExternalService, CoreService, ApiResponder;

    public function getList($directory)
    {
        return $this->performRequestQuery('GET', 'api/v1/directories/' . $directory . '/list', $this->request);
    }

    public function getTable($directory)
    {
        return $this->performRequestQuery('GET', 'api/v1/directories/' . $directory . '/table', $this->request);
    }

    public function createItems($directory, $data=[])
    {
        if($data){
            $this->request = array_merge($this->request, $data);
        }

        return $this->performRequestQuery('POST', 'api/v1/directories/' . $directory. '/list', $this->request);
    }

    public function editItems($directory, $id, $data=[])
    {
        if($data){
            $this->request = array_merge($this->request, $data);
        }

        return $this->performRequestForm('PUT', 'api/v1/directories/' . $directory . '/list/' . $id, $this->request);
    }

    public function updateHeader($directory)
    {
        return $this->performRequestForm('PUT', 'api/v1/directories/' . $directory . '/headers', $this->request);
    }

    public function toArchive($directory)
    {
        return $this->performRequestForm('POST', 'api/v1/directories/' . $directory . '/toArchive', $this->request);
    }

    public function storeAsyncValidations($directory)
    {
        return $this->performRequestQuery('GET','api/v1/directories/' . $directory . '/store-async-validations', $this->request);
    }

    public function updateAsyncValidations($directory)
    {
        return $this->performRequestQuery('GET','api/v1/directories/' . $directory . '/update-async-validations', $this->request);
    }

    public function defaultHeader($directory)
    {
        $class = CheckClassSeedsDirectory::checkClassSeed($directory);

        Artisan::call('database:company_seeds_default',[
            'class'=>$class,
            'database'=>$this->request['db'],
        ]);

        return 'Successfully';
    }
}
