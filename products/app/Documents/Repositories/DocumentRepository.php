<?php

namespace App\Documents\Repositories;

use App\Core\Helpers\PaginateHelper;
use App\Core\Helpers\UsefulHelper;
use App\Core\Repositories\Traits\RepositoryDocumentTraits;
use App\Core\Repositories\Traits\RepositoryNomenclatureTraits;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Documents\Models\Document as Model;
use App\Documents\Models\DocumentAllHeader;
use App\Documents\Models\DocumentNomenclatureOrderStockHeader;
use App\Documents\Models\DocumentNomenclatureStockHeader;
use App\Documents\Models\Warehouse\DocumentWarehousesHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\TitleJson;
use App\Documents\Models\DocumentNomenclatureHeader;
use App\Core\Model\Yesno;

/**
 * Class DocumentRepository.
 */
class DocumentRepository extends CoreRepository
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

    public function modelVisible()
    {
        return $this->model()->where('archive', Yesno::NO);
    }

    public function getDocument($id)
    {
        return $this->model()->find($id);
    }

    public function updateDocument($id, $date)
    {
        return $this->model()->find($id)->update([
            'date' => $date
        ]);
    }

    public function getDocumentReceiptStocks($id)
    {
        return $this->model()->with('document_receipt_stocks', 'document_write_off_stocks', 'document_transfer_stocks', 'document_purchases', 'document_orders', 'nomenclatures')->find($id);
    }

    public function getSelectDocumentReceiptStocksTable($id)
    {
        $document = $this->model()->with([
            'nomenclatures' => function ($q) use ($id) {
                $q->with([
                    'prices' => function ($q) use ($id) {
                        $q->wherePivot('document_id', $id);
                    },
                    'product' => function ($q) {
                        $q->with(['units']);
                    }
                ]);
            }
        ])->find($id);

        $nomenclatures = $this->getProductsDocumentsTable($document->nomenclatures);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        $data = [
            'id' => $document->id,
            'created_at' => $document->created_at,
            'sum_price' => $nomenclatures['sum_price'],
            'sum_balance' => $nomenclatures['sum_balance'],
            'nomenclatures' => [
                'headers' => $this->getDocumentNomenclatureHeader(),
                'body' => $nomenclature_array,
            ]
            //'nomenclatures' => $nomenclature_array,
        ];

        return $data;
    }

    public function getDocumentTable($id, $warehouse_id = null)
    {
        $document = $this->model()->with([
            'document_receipt_stocks',
            'document_write_off_stocks',
            'document_transfer_stocks',
            'document_purchases',
            'document_orders',
            'nomenclatures' => function ($q) use ($id, $warehouse_id) {
                $q->with([
                    'prices' => function ($q) use ($id) {
                        $q->wherePivot('document_id', $id);
                    },
                    'product' => function ($q) {
                        $q->with(['units']);
                    },
                    'service' => function ($q) {
                        $q->with(['units']);
                    },
                    'warehouses' => function ($q) use ($warehouse_id) {
                        $q->wherePivot('warehouse_id', $warehouse_id);
                    },
                ]);
            }
        ])->find($id);

        $nomenclatures = $this->getProductsDocumentsTable($document->nomenclatures);
        $nomenclature_array = [];
        $data = [];

        if (isset($document->document_receipt_stocks)) {

            if (isset($nomenclatures['arr']['product'])) {
                foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
            }

            $organization_convert_id = $document->document_receipt_stocks->organization_convert_id;
            $data = [
                'receipt_date_scheduled' => $document->document_receipt_stocks->receipt_date_scheduled,
                'warehouse' => $document->document_receipt_stocks->warehouse->title,
                'organization' => $document->document_receipt_stocks->organization->title ?? null,
                'currency' => optional($document->document_receipt_stocks->currency)->id ? TitleJson::getArray($document->document_receipt_stocks->currency->title, $this->lang) : null,
                'responsible' => $document->document_receipt_stocks->responsible->full_name ?? null,
                'comments' => $document->document_receipt_stocks->comments,
            ];
            $headers = $this->getDocumentNomenclatureHeader();
        } elseif (isset($document->document_write_off_stocks)) {

            if (isset($nomenclatures['arr']['product'])) {
                foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
            }

            $organization_convert_id = $document->document_write_off_stocks->organization_convert_id;
            $data = [
                'receipt_date_scheduled' => $document->document_write_off_stocks->receipt_date_scheduled,
                'warehouse' => $document->document_write_off_stocks->warehouse->title,
                'organization' => $document->document_write_off_stocks->organization->title ?? null,
                'currency' => optional($document->document_write_off_stocks->currency)->id ? TitleJson::getArray($document->document_write_off_stocks->currency->title, $this->lang) : null,
                'responsible' => $document->document_write_off_stocks->responsible->full_name ?? null,
                'comments' => $document->document_write_off_stocks->comments,

            ];
            $headers = $this->getDocumentNomenclatureStockHeader();
        } elseif (isset($document->document_transfer_stocks)) {

            if (isset($nomenclatures['arr']['product'])) {
                foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
            }

            $organization_convert_id = $document->document_transfer_stocks->organization_convert_id;
            $data = [
                'receipt_date_scheduled' => $document->document_transfer_stocks->receipt_date_scheduled,
                'warehouse' => $document->document_transfer_stocks->warehouse->title,
                'to_warehouse' => $document->document_transfer_stocks->to_warehouse->title,
                'organization' => $document->document_transfer_stocks->organization->title ?? null,
                'currency' => optional($document->document_transfer_stocks->currency)->id ? TitleJson::getArray($document->document_transfer_stocks->currency->title, $this->lang) : null,
                'responsible' => $document->document_transfer_stocks->responsible->full_name ?? null,
                'comments' => $document->document_transfer_stocks->comments,
                'delivery' => $document->document_transfer_stocks->delivery,

            ];
            $headers = $this->getDocumentNomenclatureStockHeader();
        } elseif (isset($document->document_purchases)) {

            if (isset($nomenclatures['arr']['product'])) {
                foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                    $nomenclature_array['product'][] = $nomenclature;
                }
            }

            if (isset($nomenclatures['arr']['service'])) {
                foreach ($nomenclatures['arr']['service'] as $nomenclature) {
                    $nomenclature_array['service'][] = $nomenclature;
                }
            }

            $organization_convert_id = $document->document_purchases->organization_convert_id;
            $data = $document->document_purchases;
            $data->makeHidden('cells', 'id', 'archive');
            $data = $data->toArray();
            $headers = [
                'product'   => $this->getDocumentNomenclatureHeader(),
                'service'   => $this->getDocumentNomenclatureHeader()
            ];
        } elseif (isset($document->document_orders)) {

            if (isset($nomenclatures['arr']['product'])) {
                foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                    $nomenclature_array['product'][] = $nomenclature;
                }
            }

            if (isset($nomenclatures['arr']['service'])) {
                foreach ($nomenclatures['arr']['service'] as $nomenclature) {
                    $nomenclature_array['service'][] = $nomenclature;
                }
            }

            $organization_convert_id = $document->document_orders->organization_convert_id;
            $data = $document->document_orders;
            $data->makeHidden('cells', 'id', 'archive');
            $data = $data->toArray();

            $headers = [
                'product'   => $this->getDocumentNomenclatureOrderStockHeader(),
                'service'   => $this->getDocumentNomenclatureHeader()
            ];
        }

        $data_basis = [
            'id' => $document->id,
            'organization_convert_id' => $organization_convert_id ?? null,
            'created_at' => $document->created_at,
            'sum_price' => $nomenclatures['sum_price'],
            'sum_balance' => $nomenclatures['sum_balance'],
            'nomenclatures' => [
                'headers' => $headers,
                'body' => $nomenclature_array,
            ]
        ];

        $data = array_merge($data, $data_basis);
        return $data;
    }

    public function createDocument()
    {
        return $this->model()->create([
            'status' => Model::NO_CAPITALIZED,
            'date' => $this->request->date
        ]);
    }

    public function createCapitalizedDocument()
    {
        return $this->model()->create([
            'status' => Model::CAPITALIZED,
            'date' => $this->request->date
        ]);
    }

    public function capitalizedDocument($id)
    {
        return $this->model()->find($id)->update([
            'status' => Model::CAPITALIZED,
            'date' => $this->request->date
        ]);
    }

    public function canceledCapitalizedDocument($id)
    {
        return $this->model()->find($id)->update([
            'status' => Model::CANCELED_CAPITALIZED,
            //'date'  => $this->request->date
        ]);
    }

    public function createDocNomen($document, $nomenclatures, $warehouse_id)
    {
        $document->nomenclatures()->detach();
        $document->warehouses()->detach();

        if (isset($nomenclatures)) {
            foreach ($nomenclatures as $nomenclature) {
                $document->nomenclatures()->attach($nomenclature['id'], [
                    'balance' => $nomenclature['balance_base'],
                    'is_packing' => $nomenclature['is_packing'],
                ]);
            }
        }

        $document->warehouses()->attach($warehouse_id);
        return $document->nomenclatures()->get();
    }

    public function createCapitalizedDocNomen($document, $nomenclatures, $warehouse_id)
    {
        if (isset($nomenclatures)) {
            foreach ($nomenclatures as $nomenclature) {
                $document->nomenclatures()->attach($nomenclature['id'], [
                    'balance' => $nomenclature['balance_base'],
                    'is_packing' => $nomenclature['is_packing'],
                    //'capitalized_balance' => $nomenclature['balance']
                ]);
            }
        }

        $document->warehouses()->attach($warehouse_id);
        return $document->nomenclatures()->get();
    }

    public function getWarehouseDocuments($id)
    {
        $data = $this->model()->where('archive', Yesno::NO)->whereHas('warehouses', function ($q) use ($id) {
            $q->where('warehouse_id', $id);
        })->with([
            'document_receipt_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_write_off_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_transfer_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_purchases',
            'document_orders',
            'warehouses'
        ])->orderBy('created_at', 'DESC')->paginate(10);

        $data = $this->getTable($data, DocumentWarehousesHeader::class);

        return $data;
    }

    public function getWarehouseDocumentsAll()
    {
        $data = $this->model()->where('archive', Yesno::NO)->whereHas('warehouses')->with([
            'document_receipt_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_write_off_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_transfer_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_purchases',
            'document_orders',
            'warehouses'
        ])->orderBy('date', 'DESC')->paginate($this->request->paginate);

        $data = $this->getTable($data, DocumentWarehousesHeader::class);

        return $data;
    }

    public function getDocumentsAll()
    {
        $data = $this->model()->where('archive', Yesno::NO)->whereHas('warehouses')->with([
            'document_receipt_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_write_off_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_transfer_stocks' => function ($q) {
                $q->with('currency');
            },
            'document_purchases',
            'document_orders',
            'warehouses'
        ])->orderBy('date', 'DESC')->paginate($this->request->paginate);

        $data = $this->getTableDocument($data, DocumentAllHeader::class);

        return $data;
    }

    public function getNomenclaturesDocuments()
    {
        $data = $this->request->all();
        $results = $this->model()->whereIn('id', $data['data'])
            ->with([
                'nomenclatures' => function ($q) use ($data) {
                    $q->where('is_actual', Yesno::YES)
                        ->where('archive', Yesno::NO)
                        ->with([
                            'product',
                            'prices' => function ($q) use ($data) {
                                $q->whereIn('price_id', $data['type_prices'])->wherePivot('status', Yesno::YES)->with('dependentPrices');
                            }
                        ])
                        ->orderBy('order', 'ASC');
                },
            ])->get();

        $nomenclatures = $this->getPricingTable($results, $data['option_prices']);
        $product_paginate = PaginateHelper::paginate(collect($nomenclatures), $this->request->paginate);

        $results = [
            'body' => UsefulHelper::iterateOverArray($product_paginate->items()),
            'total_page' => $product_paginate->lastPage(),
            'total' => $product_paginate->total()
        ];

        return $results;
    }

    public function getHeaders()
    {
        return TitleJson::get(DocumentWarehousesHeader::get(), $this->lang);
    }

    public function getDocumentWarehouseHeaders($id)
    {
        return DocumentWarehousesHeader::find($id);
    }

    public function getDocumentNomenclatureHeader()
    {
        return TitleJson::get(DocumentNomenclatureHeader::get(), $this->lang);
    }

    public function getDocumentNomenclatureStockHeader()
    {
        return TitleJson::get(DocumentNomenclatureStockHeader::get(), $this->lang);
    }

    public function getDocumentNomenclatureOrderStockHeader()
    {
        return TitleJson::get(DocumentNomenclatureOrderStockHeader::get(), $this->lang);
    }

    public function getDocumentNomenHeader($id)
    {
        return DocumentNomenclatureHeader::find($id);
    }

    public function updateDocumentNomenclaturesHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getDocumentNomenHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateDocumentWarehousesHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getDocumentWarehouseHeaders($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }
    //

    public function getDocumentAllHeader()
    {
        return TitleJson::get(DocumentAllHeader::get(), $this->lang);
    }

    public function getDocumentAllHeaders($id)
    {
        return DocumentAllHeader::find($id);
    }

    public function updateDocumentAllHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getDocumentAllHeaders($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function toArchive()
    {
        foreach ($this->request->data as $item) {
            $data = $this->modelVisible()->find($item['id']);
            $data->archive = Yesno::YES;
            $data->save();
        }
    }

    public function getDocumentNomenclatures($id)
    {
        return $this->model()->with('nomenclatures')->find($id);
    }

    public function getOpenBasket($data)
    {
        $nomenclatures = $this->getOpenBasketTable($data);

        $headers = [
            'product'   => $this->getDocumentNomenclatureHeader(),
            'service'   => $this->getDocumentNomenclatureHeader()
        ];

        if (isset($nomenclatures['arr']['product'])) {
            foreach ($nomenclatures['arr']['product'] as $nomenclature) {
                $nomenclature_array['product'][] = $nomenclature;
            }
        }

        if (isset($nomenclatures['arr']['service'])) {
            foreach ($nomenclatures['arr']['service'] as $nomenclature) {
                $nomenclature_array['service'][] = $nomenclature;
            }
        }

        $data = [
            'sum_price' => $nomenclatures['sum_price'],
            'sum_balance' => $nomenclatures['sum_balance'],
            'nomenclatures' => [
                'headers' => $headers,
                'body' => $nomenclature_array,
            ]
        ];

         return $data;
        //return $this->model()->with('nomenclatures')->find($id);
    }
}
