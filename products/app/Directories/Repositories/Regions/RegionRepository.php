<?php

namespace App\Directories\Repositories\Regions;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Regions\DirectoryRegion as Model;
use App\Directories\Models\Regions\DirectoryRegionsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class RegionRepository extends CoreRepository
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
        $data = $this->modelVisible();

        if($country_id = $this->request->country_id) {
            $data->where('country_id', $country_id);
        }

        if($selected_id = $this->request->selected_id) {
            $data->whereNotIn('id',[$selected_id]);
        }

        $data = $data->paginate($this->request->paginate);

        $data->makeVisible(['title']);
        $data->makeHidden(['cells','country_id', 'is_default', 'is_editable']);
        $rezult = $data->toArray();

        if($selected_id && $this->request->page <= 1){
            if($region = $this->modelVisible()->find($selected_id)){
                if(($this->request->selected_id && !$this->request->country_id) || ($region->country_id == $this->request->country_id)){
                    array_unshift($rezult['data'], [
                        'id'    => (int) $selected_id,
                        'title' => $region->title
                    ]);
                }
            }
        }

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

        $data = $this->getTable($data, DirectoryRegionsHeader::class);

        return $data->toArray();
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

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->with([
            'country'
        ])->find($id)->makeVisible(['title']);

        $data = [
            'id'      => str_pad($item->id, 10, '0', STR_PAD_LEFT),
            'title'   => $this->request->title ?? TitleJson::getArray($item->title, $this->lang) ?? null,
            'country' => optional($item->country)->title ? TitleJson::getArray($item->country->title, $this->lang) ?? null : null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryRegionsHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryRegionsHeader::class);
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
