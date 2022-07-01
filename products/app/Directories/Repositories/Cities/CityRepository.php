<?php

namespace App\Directories\Repositories\Cities;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Cities\DirectoryCity as Model;
use App\Directories\Models\Cities\DirectoryCitiesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class CityRepository extends CoreRepository
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
        return $this->modelVisible()->orderBy('id', 'desc')->paginate($this->request->paginate);
    }

    public function list()
    {
        $data = $this->modelVisible()
            ->where(function ($query){
                if($region_id = $this->request->region_id) {
                    $query->where('region_id', $region_id);
                }
            })
            ->paginate($this->request->paginate);

        $data->makeVisible(['title']);
        $data->makeHidden(['cells','region_id', 'is_default', 'is_editable', 'zip_code', 'country_id']);
        $rezult = $data->toArray();

        foreach ($rezult['data'] as $key=>$item){
            if(isset($item['title'])){
                $rezult['data'][$key]['title'] = TitleJson::getArray($item['title'], $this->lang);
            }
        }
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

        $data = $this->getTable($data, DirectoryCitiesHeader::class);

        return $data->toArray();
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id)->makeVisible(['title']);

        $data = [
            'id'           => str_pad($item->id, 10, '0', STR_PAD_LEFT),
            'city'         => $this->request->title ?? TitleJson::getArray($item->title, $this->lang) ?? null,
            'region'       => optional($item->region)->title ? TitleJson::getArray($item->region->title, $this->lang) ?? null : null,
            'country'      => optional($item->region->country)->title ? TitleJson::getArray($item->region->country->title, $this->lang) ?? null : null,
            'zip_code'     => $item->zip_code ?? null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryCitiesHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function createItem()
    {
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        $item = $this->modelVisible()->create($data);

        return $item;
    }

    public function updateItem($id)
    {
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        $itemMain = $this->modelVisible()->find($id);
        $itemMain->update($data);

        return $itemMain;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id)->makeHidden(['region']);
        return $this->getOneTable($item, DirectoryCitiesHeader::class);
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }

    public function changeDefaultItem()
    {
        return $this->model()->where('is_default', Yesno::YES)->update(['is_default' => Yesno::NO]);
    }
}
