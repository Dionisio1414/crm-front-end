<?php

namespace App\Directories\Repositories\CounterpartiesCancellations;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellation as Model;
use App\Directories\Models\CounterpartiesCancellations\DirectoryCounterpartiesCancellationsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class CounterpartiesCancellationsRepository extends CoreRepository
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

        $data = $this->getTable($data, DirectoryCounterpartiesCancellationsHeader::class);

        return $data->toArray();
    }

    public function createItem()
    {
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        $itemMain = $this->modelVisible()->create($data);
        $data = $this->changeCellsFormatBd(DirectoryCounterpartiesCancellationsHeader::class);
        $item = $this->modelVisible()->find($itemMain->id);
        $item->cells()->createMany($data);

        return $itemMain;
    }

    public function updateItem($id)
    {
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        $unit = $this->modelVisible()->find($id);
        $unit->update($data);
        $data = $this->changeCellsFormatBd(DirectoryCounterpartiesCancellationsHeader::class);
        $unit->cells()->delete();
        $unit->cells()->createMany($data);
        return $id;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryCounterpartiesCancellationsHeader::class);
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
