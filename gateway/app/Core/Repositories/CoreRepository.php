<?php

namespace App\Core\Repositories;

/**
 * Class CoreRepository.
 */
abstract class CoreRepository
{
    /**
     * @return string
     *  Return the model
     */

    protected $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }


    abstract protected function getModelClass();


    protected function model()
    {
        return clone $this->model;
    }
}
