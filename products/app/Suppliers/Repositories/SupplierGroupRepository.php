<?php

namespace App\Suppliers\Repositories;

use App\Suppliers\Models\SupplierGroup as Model;
use App\Warehouses\Models\WarehouseGroup;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;

/**
 * Class WarehouseRepository.
 */
class SupplierGroupRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function modelVisible()
    {
        return $this->model()->where('archive', Yesno::NO);
    }

    public function getItemsByQuery($s)
    {
        return $this->modelVisible()->where('title', 'like', '%' . $s . '%')->paginate($this->request->paginate);
    }

    public function getAll()
    {
        return $this->modelVisible()
            ->where(function ($query){
                $query->where('parent_id', Yesno::NO);
                $query->orWhereNull('parent_id');
            })
            ->with([
                'children' => function ($query) {
                    $query->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                },
            ])
            ->orderBy('order', 'ASC')->paginate($this->request->paginate);
    }

    public function list()
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s);
        }else{
            $data = $this->getAll();
        }

        return $data->toArray();
    }

    public function createItem()
    {
        return $this->model()->create($this->request->all());
    }

    public function updateItem($id)
    {
        return $this->modelVisible()->find($id)->update($this->request->all());
    }

    public function getItem($id)
    {
        return $this->modelVisible()->find($id);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }

}
