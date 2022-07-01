<?php

namespace App\Filters\Repositories\Counterparties;

use App\Directories\Repositories\Currencies\CurrencyRepository;
use App\Directories\Repositories\Organizations\OrganizationRepository;
use App\Directories\Repositories\TypesPrices\TypesPriceHeaderRepository;
use App\Directories\Repositories\TypesPrices\TypesPriceRepository;
use App\Filters\Models\CounterpartiesContract as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Services\Service\CoreDirectoriesService;
use Carbon\Carbon;

/**
 * Class PropertyRepository.
 */
class СontractsRepository extends CoreRepository
{
    use RepositoryTraits;

    protected $organizationRepository, $typesPriceRepository, $currencyRepository;

    public function __construct(CoreDirectoriesService $coreDirectoriesService, OrganizationRepository $organizationRepository, TypesPriceRepository $typesPriceRepository, CurrencyRepository $currencyRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->typesPriceRepository = $typesPriceRepository;
        $this->currencyRepository = $currencyRepository;

        parent::__construct($coreDirectoriesService);
    }

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function get()
    {
        return  $this->model()->first();
    }

    public function getItem($id)
    {
        return  $this->model()->find($id);
    }

    public function getItemByUserId($user_id = null, $is_active_filter = true)
    {
        if(!$user_id){
            return null;
        }

        return  $this->model()->where('user_id', $user_id)->where('is_active_filter',$is_active_filter)->first();
    }

    public function update()
    {
        //TODO Refactor and move to traits

        if(!$this->request->is_active_filter){
            $this->model()->where('user_id', $this->request->user_id)->delete();
            return;
        }

        $data = [
            'is_active_filter' => $this->request->is_active_filter,
            'user_id'          => $this->request->user_id
        ];

        /* Selects type */
        foreach ($this->request->input('selects') as $key=>$items){
            foreach ($items as $item){
                if(isset($item['is_selected']) && $item['is_selected']){
                    $data['selects_' .  $key . '_id'] = $item['id'];
                }
            }
        }

        /* Periods type */
        foreach ($this->request->input('periods') ?? [] as $key=>$item){
            foreach ($item as $key1=>$item1){
                $data['periods_' . $key . '_' .  $key1] = $item1 ?? null;
                $data['periods_' . $key . '_' .  $key1] = $item1 ?? null;
            }
        }

        /* Lists type */
        foreach ($this->request->input('lists') ?? [] as $key=>$item){
            $tempData = [];
            foreach ($item as $key1=>$item1){
                $tempData[] = isset($item1['is_selected']) && $item1['is_selected'] ? $item1['id'] : null;
            }
            $data['lists_' .  $key . '_ids'] = $tempData;
        }

        /* Booleans type */
        foreach ($this->request->input('booleans') ?? [] as $key=>$item){
            $data['booleans_' .  $key] = $item;
        }

        /* Ranges type */
        foreach ($this->request->input('ranges') ?? [] as $key=>$item){
            foreach ($item as $key1=>$item1){
                $data['ranges_' . $key . '_' .  $key1] = $item1 ?? null;
                $data['ranges_' . $key . '_' .  $key1] = $item1 ?? null;
            }
        }

        $this->model()->updateOrCreate([
            'user_id'=>$data['user_id']
        ],$data);

        return;
    }

    public function _setSelectsParam($data, $list)
    {
        foreach ($list as $key=>$item){
            $list[$key]['is_selected'] = false;

            if($item['id'] == $data){
                $list[$key]['is_selected'] = true;
            }

            if(!$data){
                $list[0]['is_selected'] = true;
            }
        }

        return $list;
    }

    public function _setListParam($data, $list)
    {
        foreach ($list as $key=>$item){
            $list[$key]['is_selected'] = false;
            if(in_array($item['id'], $data ?? [])){
                $list[$key]['is_selected'] = true;
            }

            if(!$data){
                $list[$key]['is_selected'] = true;
            }
        }

        return $list;
    }

    public function filter()
    {
        // add selected items
        $organizations = $this->organizationRepository->list();
        $price_types = $this->typesPriceRepository->list();
        $currencies = $this->currencyRepository->list();
        $type_contracts = $this->getDirectoryCoreList('types_contract');

        //TODO Refactor and move to traits

        $savedFilter = $this->getItemByUserId($this->request->user_id, true);

        $data = [
            'selects' => [
                'type_contracts' => $this->_setSelectsParam(optional($savedFilter)->selects_type_contracts_id, $type_contracts),
            ],
            'periods' => [
                'period_date' => [
                    'from' => optional($savedFilter)->periods_period_date_from ?? Carbon::now()->subMonth(),
                    'to'   => optional($savedFilter)->periods_period_date_to ?? Carbon::now()
                ]
            ],

            'lists' => [
                'organizations' => $this->_setListParam(optional($savedFilter)->lists_organizations_ids, $organizations['data'] ?? []),
                'price_types' => $this->_setListParam(optional($savedFilter)->lists_price_types_ids, $price_types['data']['types'] ?? []),
                'currencies' => $this->_setListParam(optional($savedFilter)->lists_currencies_ids , $currencies['data'] ?? []),
            ],
            'booleans' => [
                'is_contract_signed' => optional($savedFilter)->booleans_is_contract_signed ?? true,
                'is_status_contract' => optional($savedFilter)->booleans_is_status_contract ?? true,
            ],
            'ranges' => [
                'percent' => [
                    'from' => optional($savedFilter)->ranges_percent_from ?? -3,
                    'to'   => optional($savedFilter)->ranges_percent_to ?? 5
                ],
                'due_date' => [
                    'from' => optional($savedFilter)->ranges_due_date_from ?? 3,
                    'to'   => optional($savedFilter)->ranges_due_date_to ?? 21
                ]
            ],
            'is_active_filter' => optional($savedFilter)->is_active_filter ?? false,
        ];

        return $data;
    }
}
