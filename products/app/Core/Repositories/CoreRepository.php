<?php

namespace App\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Services\Service\CoreDirectoriesService;
//use Your Model

/**
 * Class CoreRepository.
 */
abstract class CoreRepository
{
    /**
     * @return string
     *  Return the model
     */

    protected $model, $lang, $lang_id, $request, $coreDirectoriesService;

    public function __construct()
    {
        /* Set lang */
        $this->lang = request('lang') ?? config('app.lang');
        $this->lang_id = request('lang_id') ?? config('app.lang_id');

        $this->model = app($this->getModelClass());
        $this->request = request();

        $coreDirectoriesService = new CoreDirectoriesService();
        $this->coreDirectoriesService = $coreDirectoriesService;
    }


    abstract protected function getModelClass();


    protected function model()
    {
    	return clone $this->model;
    }
}
