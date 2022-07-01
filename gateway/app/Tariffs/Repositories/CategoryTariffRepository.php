<?php

namespace App\Tariffs\Repositories;

use App\Tariffs\Model\CategoryTariff as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class CharacteristicRepository.
 */
class CategoryTariffRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCategoryTariffs()
    {
        return $this->model()->get();
    }
}
