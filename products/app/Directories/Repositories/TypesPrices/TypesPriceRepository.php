<?php

namespace App\Directories\Repositories\TypesPrices;

use App\Core\Helpers\TitleJson;
use App\Directories\Models\TypesPrices\DirectoryTypesPrice as Model;
use App\Directories\Models\TypesPrices\DirectoryTypesPricesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Core\Model\Yesno;
use App\Directories\Models\Currencies\DirectoryCurrency;
use App\Core\Traits\ApiResponder;
use App\Nomenclatures\Models\SettingPricesHeader;

/**
 * Class PropertyRepository.
 */
class TypesPriceRepository extends CoreRepository
{
    use RepositoryTraits, ApiResponder;

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
        return $this->model()->where('archive', Yesno::NO)
                             ->orderBy('is_default', 'desc')
                             ->orderBy('default', 'desc');
    }

    public function list()
    {
        $data = $this->modelVisible()->paginate($this->request->paginate);
        $data->makeHidden(['cells']);
        $rezult = $data->toArray();

        $new_data = [];
        foreach ($rezult['data'] as $key => $item) {

            if (isset($item['title'])) {
                $new_data['types'][] = [
                    'id' => $item['id'],
                    'title' => TitleJson::getArray($item['title'], $this->lang),
                    'is_buy' => $item['is_buy'],
                    'symbol_currency' => TitleJson::getArray(isset($item['symbol_currency']) ? $item['symbol_currency'] : [], $this->lang)
                ];
            }

            $new_data['margin_percents'][] = [
                'id' => $item['id'],
                'title' => $item['margin_percent']
            ];
        }

        $rezult['data'] = $new_data;

        return $rezult;
    }

    public function getAll()
    {
        return $this->modelVisible()->orderBy('created_at', 'DESC')->paginate($this->request->paginate);
    }

    public function getItemsByQuery($s)
    {
        return $this->searchTitleByJson($this->modelVisible(), 'cells', $s);
    }

    public function getItemsTable()
    {
        if ($this->request->s) {
            $data = $this->getItemsByQuery($this->request->s);
        } else {
            $data = $this->getAll();
        }

        $data = $this->getTable($data, DirectoryTypesPricesHeader::class);
        $data->makeHidden(['title', 'symbol_currency', 'currency_id', 'type_price_id', 'title_price_list']);
        return $data->toArray();
    }

    public function createItem()
    {
        //change format title and title_price_list
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        if (isset($data['core_title']) && $data['core_title']) {
            $data['core_title'] = $this->changeCustomFieldFormatBd('core_title');
        }
        if (isset($data['core_title_price_list']) && $data['core_title_price_list']) {
            $data['core_title_price_list'] = $this->changeCustomFieldFormatBd('core_title_price_list');
        }

        return $this->modelVisible()->create($data);
    }

    public function createOrUpdateCells($id)
    {
        $item = $this->modelVisible()->find($id);

        $type_price = null;
        if ($item->core_type_price_id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById('type_prices', $item->core_type_price_id))) {
                $type_price = TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        $is_buy = null;
        if ($directory_service = json_decode($this->coreDirectoriesService->getList('price_assignment'))) {
            foreach ($directory_service->data as $service) {

                if ($item->is_buy && $service->directory_id == Yesno::YES) {
                    $is_buy = TitleJson::getObjectLanguage($service->title, $this->lang_id);
                } else if (!$item->is_buy && $service->directory_id != Yesno::YES) {
                    $is_buy = TitleJson::getObjectLanguage($service->title, $this->lang_id);
                }
            }
        }

        $calculate_margin_percent = null;
        if($item->core_type_price_calculate_margin_percent_id){
            $item_type_price = $this->getItem($item->core_type_price_calculate_margin_percent_id);
            $calculate_margin_percent = $item_type_price->core_title;
        }

        $data = [
            'code' => $item->id ? str_pad($item->id, 8, '0', STR_PAD_LEFT) : null,
            'title' => $item->core_title ? TitleJson::getArray($item->core_title, $this->lang) : null,
            'title_price_list' => $item->core_title_price_list ? TitleJson::getArray($item->core_title_price_list, $this->lang) : null,
            'currency' => optional($item->currency)->title ? TitleJson::getArray($item->currency->title, $this->lang) : null,
            'type_price' => $type_price,
            'margin_percent' => $item->margin_percent ?? null,
            'is_buy' => $is_buy,
            'calculate_margin_percent' => $calculate_margin_percent
        ];

        $data = $this->changeCellsCustomFormatBd($data, DirectoryTypesPricesHeader::class);
        $item->cells()->delete();
        $item->cells()->createMany($data);
    }

    public function updateItem($id)
    {
        $data = $this->request->all();
        if(isset($data['is_default']) && $data['is_default']){
            $this->changeDefaultItem();
        }

        if (isset($data['core_title']) && $data['core_title']) {
            $data['core_title'] = $this->changeCustomFieldFormatBd('core_title');
        }
        if (isset($data['core_title_price_list']) && $data['core_title_price_list']) {
            $data['core_title_price_list'] = $this->changeCustomFieldFormatBd('core_title_price_list');
        }

        $item = $this->modelVisible()->find($id);
        $item->update($data);
        return $item;
    }

    public function getItem($id)
    {
        $item = $this->modelVisible()->find($id);
        if ($item) {
            $item->makeHidden(['title', 'symbol_currency', 'currency_id', 'type_price_id', 'title_price_list']);
            //format tables
            if ($item->core_title) {
                $item->core_title = TitleJson::getArray($item->core_title, $this->lang);
            } else {
                $item->core_title = TitleJson::getArray($item->title, $this->lang);
            }

            if ($item->core_title_price_list) {
                $item->core_title_price_list = TitleJson::getArray($item->core_title_price_list, $this->lang);
            } else {
                $item->core_title_price_list = TitleJson::getArray($item->title_price_list, $this->lang);
            }

            return $this->getOneTable($item, DirectoryTypesPricesHeader::class);
        }

        return null;
    }

    public function getTypePrices()
    {
        return $this->modelVisible()->whereIn('id', $this->request->type_prices)->with('dependentPrices')->get();
    }

    public function getTypePricesHeaders($items)
    {
        $headers_price = [];
        $options = $this->request->option_prices;
        $headers_base = TitleJson::get(SettingPricesHeader::get(), $this->lang);

        foreach ($headers_base as $header) {
            if ($options['show_min_price'] && $header->value == 'min_price') {
                $header->is_visible = Yesno::YES;
            }
        }

        foreach ($items as $key => $value) {
            $headers_price[$key] = [
                'id' => $value->id,
                'title' => TitleJson::getArray($value->title, $this->lang),
                'value' => 'price_' . $value->id
            ];

            if ($options['show_current_price']) {
                $headers_price[$key]['list_prices']['current_price'] = [
                    'title' =>  TitleJson::getArray(Model::CURRENT_PRICE, $this->lang),
                    'value' => 'current_price_' . $value->id
                ];
            }

            if ($options['show_variance_price']) {
                $headers_price[$key]['list_prices']['variance_price'] =
                [
                    'title' => TitleJson::getArray(Model::VARIANCE_PRICE, $this->lang),
                    'value' => 'variance_price_' . $value->id
                ];
            }

            $headers_price[$key]['list_prices']['new_price'] =
                [
                    'title' =>  TitleJson::getArray(Model::NEW_PRICE, $this->lang),
                    'value' => 'new_price_' . $value->id
                ];

            if ($options['show_dependent_price']) {
                foreach ($value->dependentPrices as $k => $val){

                    $headers_price[$key]['dependent_prices'][$k]['id'] = $val->id;

                    if ($val->core_title_price_list) {
                        $headers_price[$key]['dependent_prices'][$k]['title'] = TitleJson::getArray($val->core_title_price_list, $this->lang);
                    } else {
                        $headers_price[$key]['dependent_prices'][$k]['title'] = TitleJson::getArray($val->title_price_list, $this->lang);
                    }
                    $headers_price[$key]['dependent_prices'][$k]['title_type'] =  TitleJson::getArray(Model::NEW_PRICE, $this->lang);
                    $headers_price[$key]['dependent_prices'][$k]['value'] = 'dependent_price_' . $val->id;
                    $headers_price[$key]['dependent_prices'][$k]['margin_percent'] = $val->margin_percent;
                    //$headers_price[$key]['dependent_prices'][]['price_' . $val->id] =
                }
            }
        }

        $headers_base = collect($headers_base);
        $headers_price = collect($headers_price);

        $headers = $headers_base->merge($headers_price);

        return $headers;
    }


    public function toArchive()
    {
        foreach ($this->request->data as $item) {
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
