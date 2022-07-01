<?php

namespace App\Directories\Repositories\Counterparties;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\Counterparties\DirectoryCounterparty as Model;
use App\Directories\Models\Counterparties\DirectoryCounterpartiesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Services\Service\CoreDirectoriesService;
use App\Suppliers\Repositories\SupplierRepository;

/**
 * Class PropertyRepository.
 */
class CounterpartiesRepository extends CoreRepository
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
        return $this->model()->where('archive', Yesno::NO);
    }

    public static function modelVisibleStatic()
    {
        return Model::where('archive', Yesno::NO);
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function getById($id)
    {
        return $this->modelVisible()->find($id);
    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $rezult = $data->toArray();

        $new_data = [];
        foreach ($rezult['data'] as $key=>$item){
            $new_data[] = [
                'id'=>$item['id'],
                'title'=>TitleJson::getArray($item['title'], $this->lang)
            ];
        }

        $rezult['data'] = $new_data;

        return $rezult;
    }

    public function getItemsTable()
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s);
        }else{
            $data = $this->getAll();
        }

        $data = $this->getTable($data, DirectoryCounterpartiesHeader::class);

        return $data->toArray();
    }

    public function createOrUpdateItem($field, $request_data)
    {
        return $this->modelVisible()->updateOrCreate([
            $field=>$request_data[$field]
        ],$request_data);
    }

    public function createOrUpdateCells($id, $request_data)
    {
        $item = $this->modelVisible()->find($id);

        $data = [
            'id' => str_pad($item->id, 10, '0', STR_PAD_LEFT),
            'counterparty' => $request_data['title_supplier'] ?? null,
            'debt' => null, /* TODO CREATE FROM DOCUMENTS */
            'is_supplier' => $request_data['title_supplier'] ? Yesno::YES : Yesno::NO,
            'is_customer' => Yesno::NO, /* TODO CREATE FROM DOCUMENTS */
            'is_other' => Yesno::NO, /* TODO CREATE FROM DOCUMENTS */
            'contact_detail' => $request_data['contact_detail']
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryCounterpartiesHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function getIndividualsByCounterparty($id)
    {
        $data = $this->getById($id);

        /* Get Supplier */
        if(optional($data)->supplier_id){
            $supplierRepository = new SupplierRepository();
            if($suppliers = $supplierRepository->getItem($data->supplier_id)->contacts){
                $rezult = [];
                foreach ($suppliers as $supplier){
                    $rezult[] = [
                        'id'    => $supplier->id,
                        'title' => $supplier->full_name
                    ];
                }

                return $rezult;
            }
            return [];
        }
//        else if($data->customer_id){
//
//        }else if(){
//
//        }

        return [];
    }

    public function getIndividualById($counterparty_id, $id)
    {
        $data = $this->getById($counterparty_id);

        /* Get Supplier */
        if(optional($data)->supplier_id){
            $supplierRepository = new SupplierRepository();
            if($suppliers = $supplierRepository->getContactById($data->supplier_id, $id)){
                return $suppliers;
            }
            return [];
        }
    }

//    public function toArchive()
//    {
//        foreach ($this->request->data as $item){
//            $data = $this->modelVisible()->find($item['id']);
//            $data->archive = Yesno::YES;
//            $data->save();
//        }
//    }
}
