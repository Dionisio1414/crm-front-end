<?php

namespace App\Directories\Repositories\Organizations;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Cities\DirectoryCity;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Directories\Models\Organizations\DirectoryOrganization as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Directories\Models\Regions\DirectoryRegion;

/**
 * Class PropertyRepository.
 */
class OrganizationRepository extends CoreRepository
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
        return $this->model()
            ->where('archive', Yesno::NO)
            ->orderBy('is_default','desc')
            ->orderBy('order','desc');
    }

    public function getAll()
    {
        $data = $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);

        /* Set Languages in Object Country, Regions, Cities */
        $data = $this->getParamTitleAttributes($data,
            [
                'additional' => [
                    'contact' => [
                        'values.country_title' => 'country_id',
                        'values.region_title' => 'region_id',
                        'values.city_title' => 'city_id'
                    ],
                ],
            ],
            [
                DirectoryCountry::class,
                DirectoryRegion::class,
                DirectoryCity::class,

            ]
        );

        return $data;
    }

    public function getDefault()
    {
        return $this->modelVisible()->where('is_default', Yesno::YES)->first();
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->makeHidden(['main','contact']);
        $rezult = $data->toArray();

        return $rezult;
    }

//    public function getItemsByQuery($s)
//    {
//        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
//    }

    public function createOrUpdateItem($id=null)
    {
        $data = $this->request->all();

        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        if($id){
            $this->getItem($id)->update($data);
            $item = $this->getItem($id);
        }else{
            $item = $this->modelVisible()->create($data);
            /* Update order */
            $item->update(['order'=>$item->id]);
        }

        return $item;
    }

    public function createOrUpdateMain($id)
    {
        if($this->request->main){
            $item = $this->getItem($id);
            $item->main()->updateOrCreate([
                'organization_id'=>$item->id
            ], $this->request->main);
        }
    }

    public function createOrUpdateContact($id)
    {
        if($this->request->contact){
            $item = $this->getItem($id);
            $item->contact()->updateOrCreate([
                'organization_id'=>$item->id
            ], $this->request->contact);
        }
    }

    public function createOrUpdateValues($id)
    {
        if(isset($this->request->contact['values'])){
            $item = $this->getItem($id);
            $item->contact->values()->delete();
            $item->contact->values()->createMany($this->request->contact['values']);
        }
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

    public function changeDefaultItem()
    {
        return $this->model()->where('is_default', Yesno::YES)->update(['is_default' => Yesno::NO]);
    }
}
