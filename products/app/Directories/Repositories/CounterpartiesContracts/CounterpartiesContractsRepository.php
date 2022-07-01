<?php

namespace App\Directories\Repositories\CounterpartiesContracts;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\CounterpartiesContracts\DirectoryCounterpartiesContract as Model;
use App\Directories\Models\CounterpartiesContracts\DirectoryCounterpartiesContractsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;

/**
 * Class PropertyRepository.
 */
class CounterpartiesContractsRepository extends CoreRepository
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

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function list()
    {
        if($supplier_id = $this->request->supplier_id){
            $data = $this->modelVisible()->where('supplier_id', $this->request->supplier_id)->with('counterparty_supplier')->paginate($this->request->paginate);
        }else{
            $data = $this->modelVisible()->with('counterparty_supplier')->paginate($this->request->paginate);
        }

        if(!$data){
            return [];
        }

        $data->makeHidden(['cells']);
        $rezult = $data->toArray();

        $new_data = [];

        foreach ($rezult['data'] as $key=>$item){
            $new_data[] = [
                'id' => $item['id'],
                'title' => $item['title'] . ' № ' . $item['registration_number'] . ' от ' . $item['contract_date'],
                'currency_id' => $item['currency_id'],
                'price_type_id' => $item['price_type_id'],
                'manager' => $item['counterparty_supplier']['manager'] ?? null,
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

        $data = $this->getTable($data, DirectoryCounterpartiesContractsHeader::class);
        $data = $data->toArray();

        /* Added items in cell */
        foreach ($data['data'] as $key=>$item){
            if(isset($item['cells']) && $item['cells']){
                $data['data'][$key]['cells']['is_automatically'] = $item['is_automatically'];
            }
        }

        return $data;
    }

    public function createItem()
    {
        return $this->modelVisible()->create($this->request->all());
    }

    public function updateItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->update($this->request->all());
        return $id;
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id);

        /* generate currency */
        if(optional($item->currency)->title) {
            $currencyTitle = TitleJson::getArray($item->currency->title, $this->lang);
        }

        /* generate price type */
        if(optional($item->price_type)->title) {
            $priceTitle = TitleJson::getArray($item->price_type->title, $this->lang);
        }

        $status = null;
        if($item->status_contract_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('status_contracts', $item->status_contract_id))) {
                $status = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $type_contract = null;
        if($item->type_contract_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('types_contract', $item->type_contract_id))) {
                $type_contract = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $counterparty = null;
        if($item->counterparty_supplier) {
            $counterparty = $item->counterparty_supplier->title_supplier;
        }
//        if($item->counterparty_customer) {
//            $counterparty = $item->counterparty_supplier->title_supplier;
//        }
//        if($item->counterparty_other) {
//            $counterparty = $item->counterparty_supplier->title_supplier;
//        }

        $data = [
            'id' => str_pad($item->id, 10, '0', STR_PAD_LEFT),
            'title' => $item->title,
            'registration_number' => $item->registration_number ?? null,
            'contract_date' =>  $item->contract_date ?? null,
            'period_date' => $item->from_period_date . " - " . $item->to_period_date,
            'counterparty' => $counterparty,
            'type_contract' => $type_contract,
            'organization' => optional($item->organization)->title ?? null,
            'currency' => $currencyTitle ?? null,
            'due_date' => $item->due_date ?? null,
            'price_type' =>  $priceTitle ?? null,
            'price_markup' => $item->percent ?? null,
            'is_contract_signed' => $item->is_contract_signed ? Yesno::YES : Yesno::NO,
            'status' => $status,
            'description' => $item->description ?? null,
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryCounterpartiesContractsHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function createOrUpdateItem($field, $request_data)
    {
        return $this->modelVisible()->updateOrCreate([
            $field=>$request_data[$field]
        ],$request_data);
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        return $this->getOneTable($item, DirectoryCounterpartiesContractsHeader::class);
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
