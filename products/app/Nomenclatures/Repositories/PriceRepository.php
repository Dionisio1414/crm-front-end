<?php

namespace App\Nomenclatures\Repositories;


use App\Core\Helpers\PaginateHelper;
use App\Core\Helpers\UsefulHelper;
use App\Core\Repositories\Traits\RepositoryDocumentTraits;
use App\Core\Repositories\Traits\RepositoryNomenclatureTraits;
use App\Nomenclatures\Models\NomenclaturePrice as Model;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\TitleJson;
use App\Core\Helpers\ProductsTable;
use App\Core\Model\Yesno;
use App\Nomenclatures\Models\NomenclaturePriceHeader;
use App\Nomenclatures\Models\NomenclaturePriceTableHeader;
use Carbon\Carbon;


/**
 * Class PriceRepository.
 */
class PriceRepository extends CoreRepository
{

    use RepositoryDocumentTraits, RepositoryNomenclatureTraits;

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public static function getPrice($id)
    {
        return Model::find($id);
    }

    public static function getCurrentPrice($nomenclature_id, $price_id)
    {
        return Model::where('nomenclature_id', $nomenclature_id)
            ->where('status', Yesno::YES)
            ->where('price_id', $price_id)
            ->orderBy('date', 'DESC')
            ->first();
    }

    public static function editPrice($id, $value)
    {
        $price = self::getPrice($id);
        $price->value = $value;
        $price->save();

        return $price;
    }

    public function getNomenclaturePrices()
    {
        $items = $this->model()->with([
            'nomenclature' => function ($q) {
                $q->with('product');
            },
            'price',
            'units',
            'supplier'])
            ->paginate($this->request->paginate);
        $nomenclatures = $this->getNomenclaturePricesTable($items);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $nomenclature_array;
    }

    public function getNomenclaturesPrices()
    {
        $data = $this->request->all();

        $results = $this->model()->whereIn('price_id', $data['data'])
            ->where('status', Yesno::YES)
            ->with([
                'nomenclature' => function ($q) use ($data) {
                    $q->where('is_actual', Yesno::YES)
                        ->where('archive', Yesno::NO)
                        ->whereHas('product')
                        ->with([
                            'product',
                            'prices' => function ($q) use ($data) {
                                $q->whereIn('price_id', $data['type_prices'])->wherePivot('status', Yesno::YES)->with('dependentPrices');
                            }
                        ])->orderBy('order', 'ASC');
                },
            ])->selectRaw('count(*) AS date, nomenclature_id')->groupBy('nomenclature_id')->orderBy('date', 'DESC')->get();

        $nomenclatures = $this->getPricingTable($results, $data['option_prices']);
        $product_paginate = PaginateHelper::paginate(collect($nomenclatures), $this->request->paginate);

        return $results = [
            'body' => UsefulHelper::iterateOverArray($product_paginate->items()),
            'total_page' => $product_paginate->lastPage(),
            'total' => $product_paginate->total()
        ];
        //return $results;
    }

    public function getNomenclaturePrice($id)
    {
        $items = $this->model()->where('nomenclature_id', $id)->with([
            'nomenclature' => function ($q) {
                $q->with([
                    'product',
                    'kit',
                    'service'
                ]);
            },
            'price',
            'units',
            'supplier'
        ])->paginate($this->request->paginate);

        $nomenclatures = $this->getNomenclaturePricesTable($items);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }


        return $data = [
            'body' => $nomenclature_array,
            'total_page' => $items->lastPage(),
            'total' => $items->total()
        ];
    }

    public function getNomenclaturePriceHeader($id)
    {
        return NomenclaturePriceHeader::find($id);
    }

    public function getNomenclaturePricesHeader()
    {
        return TitleJson::get(NomenclaturePriceHeader::get(), $this->lang);
    }

    public function getNomenclaturePricesTableHeader()
    {
        return TitleJson::get(NomenclaturePriceTableHeader::get(), $this->lang);
    }

    public function updateNomenclaturePriceHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getNomenclaturePriceHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function editNomenclaturePrice($items, $status, $document_id)
    {

        if (isset($items['nomenclatures'])) {
            foreach ($items['nomenclatures'] as $value) {
                $date = date("Y-m-d", strtotime($items['date']));

                $this->model()->updateOrCreate(
                    [
                        'date' => $this->model()->where('date', 'LIKE', $date . '%')->first()->date ?? null,
                        'nomenclature_id' => $value['id'],
                        'price_id' => $items['price_id'],
                        'document_id' => $document_id
                    ],
                    [
                        'nomenclature_id' => $value['id'],
                        'price_id' => $items['price_id'],
                        'value' => $value['price'],
                        'percent' => $value['percent'] ?? null,
                        'unit_id' => $items['unit_id'],
                        'supplier_id' => $items['supplier_id'] ?? null,
                        'date' => $items['date'],
                        'document_id' => $document_id,
                        'status' => $status
                    ]
                );
            }
        }
    }

    public function deletePrices($ids)
    {
        return $this->model()->whereIn('id', $ids)->delete();
    }

    public static function updateOrCreateNomenclaturePrice($id, $price_id, $unit_id, $date, $value, $supplier_id)
    {
        return Model::updateOrCreate(
            [
                'date' => Model::where('date', 'LIKE', $date . '%')->first()->date ?? Carbon::now()->toDateTimeString(),
                'nomenclature_id' => $id,
                'price_id' => $price_id
            ],
            [
                'value' => $value,
                'unit_id' => $unit_id,
                'supplier_id' => $supplier_id ?? null
            ]);
    }

    public static function updateOrCreateNomenclaturePriceSetting($id, $price_id, $unit_id, $date, $value, $supplier_id)
    {
        $date = date("Y-m-d", strtotime($date));
        return Model::updateOrCreate(
            [
                'date' => Model::where('date', 'LIKE', $date . '%')->first()->date ?? null,
                'nomenclature_id' => $id,
                'price_id' => $price_id
            ],
            [
                'value' => $value,
                'unit_id' => $unit_id,
                'supplier_id' => $supplier_id ?? null,
                'date' => $date,
            ]);
    }
}
