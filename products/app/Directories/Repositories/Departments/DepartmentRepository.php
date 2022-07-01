<?php

namespace App\Directories\Repositories\Departments;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Departments\DirectoryDepartment as Model;
use App\Directories\Models\Departments\DirectoryDepartmentsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class DepartmentRepository extends CoreRepository
{
    use RepositoryTraits;

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
        return $this->model()->where('archive', Yesno::NO)->orderBy('is_default','desc');
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->makeHidden(['cells']);
        $rezult = $data->toArray();

        $new_data = [];

        foreach ($rezult['data'] as $key=>$item){

            $title_departments = '';

            if(isset($item['department_number'])){
                $title_departments .=  '№' . trim(TitleJson::getArray($item['department_number'], $this->lang));
            }

            if(isset($item['city'])){
                $title_departments .=  ' ' . trim(TitleJson::getArray($item['city'], $this->lang));
            }

            if(isset($item['street'])){
                $title_departments .= ', ул. ' . trim(TitleJson::getArray($item['street'], $this->lang));
            }

            if(isset($item['house_number'])){
                $title_departments .= ', ' . trim(TitleJson::getArray($item['house_number'], $this->lang));
            }

            $new_data[] = [
                'id'    => $item['id'],
                'title' => $title_departments,
            ];
        }

        $rezult['data'] = $new_data;

        return $rezult;
    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
    }

    public function getItemsTable()
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s);
        }else{
            $data = $this->getAll();
        }

        $data = $this->getTable($data, DirectoryDepartmentsHeader::class);

        return $data->toArray();
    }

    public function createItem()
    {
        $itemMain = $this->modelVisible()->create($this->request->all());
        $data = $this->changeCellsFormatBd(DirectoryDepartmentsHeader::class);
        $item = $this->modelVisible()->find($itemMain->id);
        $item->cells()->createMany($data);

        return $itemMain;
    }

    public function updateItem($id)
    {
        $unit = $this->modelVisible()->find($id);
        $unit->update($this->request->all());
        $data = $this->changeCellsFormatBd(DirectoryDepartmentsHeader::class);
        $unit->cells()->delete();
        $unit->cells()->createMany($data);
        return $id;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryDepartmentsHeader::class);
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
