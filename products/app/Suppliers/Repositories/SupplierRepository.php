<?php

namespace App\Suppliers\Repositories;

use App\Core\Helpers\TitleJson;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Directories\Models\Regions\DirectoryRegion;
use App\Directories\Repositories\Counterparties\CounterpartiesRepository;
use App\Directories\Repositories\CounterpartiesContracts\CounterpartiesContractsRepository;
use App\Directories\Repositories\IndividualsDocuments\IndividualsDocumentsRepository;
use App\Directories\Repositories\Organizations\OrganizationRepository;
use App\Suppliers\Models\Adresses\SupplierDeliveriesAdress;
use App\Suppliers\Models\Supplier as Model;
use App\Suppliers\Models\Adresses\SupplierIndividualsAdress;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Directories\Models\Cities\DirectoryCity;
use App\Suppliers\Models\SuppliersHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Model\Yesno;
use App\Suppliers\Models\SupplierGroup;
use Carbon\Carbon;

/**
 * Class WarehouseRepository.
 */
class SupplierRepository extends CoreRepository
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
        return $this->model()->orderBy('created_at', 'DESC')->where('archive', Yesno::NO);
    }

    public function paginateByGroup($group_ids)
    {
        return $this->modelVisible()->whereIn('group_id', $group_ids)->paginate($this->request->paginate);
    }

    public function paginateWithoutGroup()
    {
        return $this->modelVisible()->paginate($this->request->paginate);
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->append('full_name')->makeHidden(['cells']);
        $rezult = $data->toArray();

        $new_data = [];

        foreach ($rezult['data'] as $key=>$item){
                $new_data['full_names'][] = [
                    'id' => $item['id'],
                    'title' => $item['full_name']
                ];
        }

        $rezult['data'] = $new_data;
        return $rezult;
    }

    public function getAll($group_ids = null)
    {
        if($group_ids){
            $paginator = $this->paginateByGroup($group_ids);
        }else{
            $paginator = $this->paginateWithoutGroup();
        }

        $paginator->setCollection($paginator->getCollection()->makeHidden(['employee', 'manager']));
        return $paginator;
    }

    public function getItemsByQuery($s, $group_ids = null)
    {
        if($group_ids){
            $collection = $this->paginateByGroup($group_ids)->without(['employee', 'manager']);
        }else{
            $collection = $this->paginateWithoutGroup()->without(['employee', 'manager']);
        }

        return $this->searchTitleByJson($collection, 'cells', $s);
    }

    public function getItemsTable($group_ids)
    {
        if($this->request->s){
            $data = $this->getItemsByQuery($this->request->s, $group_ids);
        }else{
            $data = $this->getAll($group_ids);
        }

        /* Set Languages in Object Country, Regions, Cities */
        $data = $this->getParamTitleAttributes($data,
            $this->_paramsAddress(),
            $this->_paramsAddress(false)
        );

        $data = $this->getTable($data, SuppliersHeader::class);

        return $data->toArray();
    }

    public function createItem()
    {
        return $this->modelVisible()->create($this->request->all());
    }

    public function updateItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->update($this->request->all());
        return $item;
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id);

        $group = null;
        if($item->group_id){
            $group = SupplierGroup::find($item->group_id);
        }

        $partner_type = null;
        if($item->partner_type_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('counterparty_type', $item->partner_type_id))) {
                $partner_type = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $actualAddress = null;
        if(count($item->actual_address)){
            $actualAddress = $item->actual_address[0]->individual_address;
        }

        $deliveryAddress = null;
        if(count($item->delivery_address)){
            $deliveryAddress = $item->delivery_address[0]->delivery_address;
        }

        $dataTemp = [
            'id' => str_pad($item->id, 10, '0', STR_PAD_LEFT),
            'title_supplier' => $item->title_supplier ?? null,
            'title_company' => $item->title_company ?? null,
            'debt' => 0, /* Get directory from zakupki */
            'actual_address_region' => $this->getCountryCityRegion(optional($actualAddress)->region_id, 'title',DirectoryRegion::class),
            'actual_address_city' => $this->getCountryCityRegion(optional($actualAddress)->city_id, 'title',DirectoryCity::class),
            'legal_address_region' => $this->getCountryCityRegion($item->legal_address_region_id, 'title',DirectoryRegion::class),
            'legal_address_city' => $this->getCountryCityRegion($item->legal_city_id, 'title',DirectoryCity::class),
            'delivery_address_country' => $this->getCountryCityRegion(optional($deliveryAddress)->country_id, 'title',DirectoryCountry::class),
            'delivery_address_region' => $this->getCountryCityRegion(optional($deliveryAddress)->region_id, 'title',DirectoryRegion::class),
            'delivery_address_city' => $this->getCountryCityRegion(optional($deliveryAddress)->city_id, 'title',DirectoryCity::class),
            'manager' => trim($item->manager) ?? null,
            'partner_type' => $partner_type,
            'group' => optional($group)->title ?? null,
            'foreign_company' => $item->is_foreign_company ? Yesno::YES : Yesno::NO,
            'inn' => $item->partner_inn ?? null,
            'edrpou' => $item->partner_edrpou ?? null,
            'actual_address_country' => $this->getCountryCityRegion(optional($actualAddress)->country_id, 'title',DirectoryCountry::class),
            'legal_address_country' => $this->getCountryCityRegion($item->legal_address_country_id, 'title',DirectoryCountry::class)
        ];

        $data = $this->changeCellsCustomFormatBd($dataTemp, SuppliersHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);

        /* Generate Counterparties, contracts and (individuals cells) */
        unset($dataTemp['id']);

        $data = $this->createDataCounterparties($item, $dataTemp);

        $this->createOrUpdateCounterpartiesItem($data);
        $this->createOrUpdateCounterpartiesContractsItem($data);
        if($item->contacts){
            /* Update Or Create documents individuals cells */
            $this->createOrUpdateIndividualsDocumentsCells($item->contacts);
        }
    }


    public function createOrUpdateActualAddress($id)
    {
        if($this->request->actual_address){
            $item = $this->getItem($id);
            $item->actual_address()->delete();
            $address_id = [];
            foreach ($this->request->actual_address as $adress){
                $rezult = SupplierIndividualsAdress::create($adress);
                $address_id[] = [
                    'individual_address_id' => $rezult->id
                ];
            }
            $item->actual_address()->createMany($address_id);
        }
    }

    public function createOrUpdateDeliveryAddress($id)
    {
        if($this->request->delivery_address){
            $item = $this->getItem($id);
            $item->delivery_address()->delete();
            $address_id = [];
            foreach ($this->request->delivery_address as $adress){
                $rezult = SupplierDeliveriesAdress::create($adress);
                $address_id[] = [
                    'delivery_address_id' => $rezult->id
                ];
            }
            $item->delivery_address()->createMany($address_id);
        }
    }

    public function createOrUpdateContactsAndDocuments($id)
    {
        $item = $this->getItem($id);


        if(count($this->request->contacts)){
            foreach($this->request->contacts as $contact){

                /* update contacts */
                $documents = $contact;
                unset($contact['documents']);

                $updateArray = [];
                if(isset($contact['id'])){
                    $relation = $item->contacts()->updateOrCreate(['id'=>$contact['id']], $contact);
                }else{
                    $relation = $item->contacts()->create($contact);
                }


                /* update documents */
                if(isset($documents['documents'])){
                    foreach ($documents['documents'] as $document){
                        if(isset($document['id'])){
                            $relation->documents()->updateOrCreate(['id'=>$document['id']], $document);
                        }else{
                            $relation->documents()->create($document);
                        }
                    }
                }
            }
        }else{
            $item->contacts()->delete();
        }
    }

    public function updateGroups()
    {
        if($this->request->data){
            foreach ($this->request->data as $data){
                $this->model()->where('id', $data['id'])->update([
                    'group_id'=>$data['group_id']
                ]);
                $this->createOrUpdateCells($data['id']);
            }
        }
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        $item->setHidden(['employee','manager']);

        $item = $this->getParamTitleAttributesSingleItem(
            $item,
            $this->_paramsAddress(),
            $this->_paramsAddress(false)
        );

        /* Format Address */
        if($item->actual_address){
            $rezult = [];
            foreach ($item->actual_address as $actual_address) {
                $rezult[] = $actual_address->individual_address;
            }
            unset($item->actual_address);
            $item->actual_address = $rezult;
        }
        if($item->delivery_address){
            $rezult = [];
            foreach ($item->delivery_address as $delivery_address) {
                $rezult[] = $delivery_address->delivery_address;
            }
            unset($item->delivery_address);
            $item->delivery_address = $rezult;
        }

        return $this->getOneTable($item, SuppliersHeader::class);
    }

    public function getContactById($individual_id, $id)
    {
        if($individuals = $this->getItem($individual_id)){
            if($individuals->contacts){
                foreach ($individuals->contacts as $individual){
                    if($individual->id == $id){
                        return $individual;
                    }
                }
            }
        }
        return [];
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item){
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }

    public function createDataCounterparties($item, $data)
    {
        $organizationRepository = new OrganizationRepository();

        $tempsData = [
            'contact_detail' => $item->phone . ',' . $item->email,
            'supplier_id' => $item->id,
            'is_automatically' => true,
            'type_contract_id' => 2,
            'currency_id' => $item->currency_id,
            'title'=> __('directory.counterparties_contracts_title'),
            'from_period_date' => Carbon::now()->format('Y-m-d'),
            'status_contract_id' => 2,
            'price_type_id' => 1,
            'organization_id' => $organizationRepository->getDefault()->id
        ];

        return array_merge($data, $tempsData);
    }

    public function createOrUpdateCounterpartiesItem($data)
    {
        $counterpartiesRepository = new CounterpartiesRepository();

        $item = $counterpartiesRepository->createOrUpdateItem('supplier_id', $data);
        $counterpartiesRepository->createOrUpdateCells($item->id,$data);
    }

    public function createOrUpdateCounterpartiesContractsItem($data)
    {
        $counterpartiesContractsRepository = new CounterpartiesContractsRepository();

        $item = $counterpartiesContractsRepository->createOrUpdateItem('supplier_id', $data);
        $counterpartiesContractsRepository->createOrUpdateCells($item->id);
    }

    public function createOrUpdateIndividualsDocumentsCells($data)
    {
        $individualsDocumentsRepository = new IndividualsDocumentsRepository();
        $individualsDocumentsRepository->createOrUpdateCells($data);
    }

    public function _paramsAddress($is_params = true)
    {
        if($is_params){
            return [
                'legal_address_country_title' => 'legal_address_country_id',
                'legal_address_region_title' => 'legal_address_region_id',
                'legal_city_title' => 'legal_city_id',
                'additional' => [
                    'actual_address' => [
                        'individual_address.country_title' => 'country_id',
                        'individual_address.region_title' => 'region_id',
                        'individual_address.city_title' => 'city_id'
                    ],
                    'delivery_address' => [
                        'delivery_address.country_title' => 'country_id',
                        'delivery_address.region_title' => 'region_id',
                        'delivery_address.city_title' => 'city_id'
                    ],
                ],
            ];
        }

        return [
            DirectoryCountry::class,
            DirectoryRegion::class,
            DirectoryCity::class,
        ];
    }
}
