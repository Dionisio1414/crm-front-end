<?php

namespace App\Directories\Repositories;

use App\Core\Traits\CoreService;
use App\Directories\Model\DirectoriesHeader as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class CharacteristicRepository.
 */
class DirectoriesRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getDirectory($provider)
    {
        return $this->model()->where('value', $provider)->first();
    }

    public function getDirectoryById($provider, $id)
    {
        $data = $this->model()->where('value', $provider)->first();
        $items = $data->items()->where('directory_id', $id)->first();
        $data->items = $items;
        return $data;
    }

}
