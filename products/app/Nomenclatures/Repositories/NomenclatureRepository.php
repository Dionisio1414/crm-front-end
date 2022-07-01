<?php

namespace App\Nomenclatures\Repositories;

//use App\Core\Helpers\UsefulHelper;
use App\Core\Repositories\Traits\RepositoryDocumentTraits;
use App\Core\Repositories\Traits\RepositoryNomenclatureTraits;
use App\Documents\Models\DocumentNomenclatureOrderStockHeader;
use App\Documents\Models\DocumentNomenclatureStockHeader;
use App\Nomenclatures\Models\Kit;
use App\Nomenclatures\Models\KitsHeader;
use App\Nomenclatures\Models\KitsTableHeader;
use App\Nomenclatures\Models\NomenclaturePriceTableHeader;
use App\Nomenclatures\Models\SelectServicesHeader;
use App\Nomenclatures\Models\TableGroupsNomenclaturesHeader;
use App\Nomenclatures\Repositories\PriceRepository;
use App\Documents\Models\DocumentNomenclatureHeader;
use App\Nomenclatures\Models\Nomenclature as Model;
use App\Nomenclatures\Models\NomenclaturePrice;
use App\Nomenclatures\Models\NomenclaturesHeader;
use App\Nomenclatures\Models\Product;
use App\Nomenclatures\Models\ProductsInRelatedHeader;
use App\Nomenclatures\Models\ProductsHeader;

//use App\Nomenclatures\Models\RelatedProduct;
use App\Nomenclatures\Models\RelatedProductsHeader;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\TitleJson;
use App\Core\Helpers\ProductsTable;
use App\Core\Model\Yesno;
use App\Nomenclatures\Models\SelectProductsHeader;
use App\Nomenclatures\Models\SelectRelatedProductsHeader;
use App\Nomenclatures\Models\Service;
use App\Nomenclatures\Models\ServicesHeader;
use App\Nomenclatures\Models\TableRelatedNomenclaturesInNomenclatureHeader;
use App\Nomenclatures\Models\TableRelatedProductsHeader;
use App\Warehouses\Models\WarehousesKitsHeader;
use App\Warehouses\Models\WarehousesProductsHeader;
use App\Core\Helpers\PaginateHelper;
use Carbon\Carbon;

/**
 * Class NomenclatureRepository.
 */
class NomenclatureRepository extends CoreRepository
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

    public function getProducts($id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getProductsTable($products, $this->getPricesHeader()),
            //'body'  => $products,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getNomenclaturesCategories()
    {
        $data = $this->request->all();
        $results = $this->model()
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $data['data'])
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices' => function ($q) use ($data) {
                    $q->whereIn('price_id', $data['type_prices'])->wherePivot('status', Yesno::YES)->with('dependentPrices');
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getPricingTable($results, $data['option_prices']);
        //$product_paginate = PaginateHelper::paginate(collect($nomenclatures), $this->request->paginate);
        $results = [
            'body' => $nomenclatures,
            'total_page' => $results->lastPage(),
            'total' => $results->total()
        ];

        return $results;
    }

    public function getNomenclaturesWarehouses()
    {
        $data = $this->request->all();

        $results = $this->model()
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('warehouses', function ($q) use ($data) {
                $q->whereIn('warehouse_id', $data['data'])->where('balance', '>', '0');
            })
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices' => function ($q) use ($data) {
                    $q->whereIn('price_id', $data['type_prices'])->wherePivot('status', Yesno::YES)->with('dependentPrices');
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getPricingTable($results, $data['option_prices']);

        $results = [
            'body' => $nomenclatures,
            'total_page' => $results->lastPage(),
            'total' => $results->total()
        ];

        return $results;
    }

    public function getNomenclaturesSetting()
    {
        $data = $this->request->all();

        $results = $this->model()
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('id', $data['data'])
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices' => function ($q) use ($data) {
                    $q->whereIn('price_id', $data['type_prices'])->wherePivot('status', Yesno::YES)->with('dependentPrices');
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getPricingTable($results, $data['option_prices']);

        $results = [
            'body' => $nomenclatures,
            'total_page' => $results->lastPage(),
            'total' => $results->total()
        ];

        return $results;
    }

    public function getKits($id)
    {
        $kits = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('kit')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'kits' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getKitsTable($kits, $this->getPricesHeader()),
            //'body'  => $products,
            'total_page' => $kits->lastPage(),
            'total' => $kits->total()
        ];
    }

    public function getSelectProducts($id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->where(function ($query) {
                $query->whereHas('product')
                    ->orWhereHas('kit');
            })
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices'
                        ]);
                },
                'kits' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices'
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getSelectProductsWarehouses($id, $warehouse_id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->where(function ($query) use ($warehouse_id) {
                //product
                $query->where(function ($qk) use ($warehouse_id) {
                    $qk->where('is_actual', Yesno::YES)
                        ->whereNull('group_id')
                        ->whereHas('warehouses', function ($q) use ($warehouse_id) {
                            $q->where('warehouse_id', $warehouse_id)->where('balance', '>', 0);
                        });
                    //groups
                })->orWhere(function ($qp) {
                    $qp->whereHas('groups', function ($q) {
                        $q->where('archive', Yesno::NO)
                            ->where('is_actual', Yesno::YES)
                            ->whereHas('warehouses', function ($q) {
                                $q->where('balance', '>', 0);
                            });
                    });
                    //kits
                })->orWhere(function ($qp) {
                    $qp->whereHas('kit')
                        ->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES);
//                        ->whereHas('warehouses', function ($q){
//                            $q->where('balance', '>', 0);
//                        });
                });
            })
//            ->where(function ($query) {
//                $query->whereHas('product')
//                    ->orWhereHas('kit');
//            })
//            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'groups' => function ($q) use ($warehouse_id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('warehouses', function ($q) use ($warehouse_id) {
                            $q->where('balance', '>', 0)->where('warehouse_id', $warehouse_id);
                        })
                        ->with([
                            'product',
//                            'warehouses' => function ($q)  use ($warehouse_id) {
//                                $q->where('archive', Yesno::NO)->where('warehouse_id', $warehouse_id)->where('balance', '>', 0);
//                            },
                            'prices'
                        ]);
                },
                'kits' => function ($q) use ($warehouse_id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) use ($warehouse_id) {
                                $q->where('archive', Yesno::NO)->where('warehouse_id', $warehouse_id)->where('balance', '>', 0);
                            },
                            'prices'
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getSelectServices($id)
    {
        $items = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('service')
            ->with([
                'service' => function ($q) {
                    $q->with(['units']);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectServicesTable($items),
            'total_page' => $items->lastPage(),
            'total' => $items->total()
        ];
    }

    public function getGroupsNomenclature($id)
    {

        return Product::whereHas('nomenclatures', function ($q) use ($id) {
            $q->where('group_id', $id);
        })->orderBy('id', 'desc')->first();
        //return $this->model()->where();
    }

    public function getGroupsNomenclatures($id)
    {
        $product = $this->model()
            ->whereHas('product')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
                'files',
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product' => function ($q) {
                                $q->with('units');
                            },
                            'prices' => function ($q) {
                                $q->where('status', Yesno::YES);
                            },
                        ]);
                },
            ])->orderBy('order', 'ASC')->find($id);


        if (isset($product->groups)) {
            $product_paginate = PaginateHelper::paginate($product->groups, $this->request->paginate);

            $nomenclatures = $this->tableRelatedNomenclaturesInNomenclature($product_paginate, $this->getPricesHeader());

            if (isset($nomenclatures['arr'])) {
                foreach ($nomenclatures['arr'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
            } else {
                $nomenclature_array = [];
            }

            return $data = [
                'headers' => $this->getTableGroupsNomenclaturesHeader(),
                'title' => $product->product->short_title,
                'convert_id' => $product->product->convert_id,
                'sku' => $product->product->sku,
                'body' => $nomenclature_array,
                'total_page' => $product_paginate->lastPage(),
                'total' => $product_paginate->total()
            ];
        } else {
            return $data = [
                'headers' => $this->getTableGroupsNomenclaturesHeader(),
                'body' => [],
            ];
        }
    }

    public function getSelectProductsAll()
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->where(function ($query) {
                $query->whereHas('product')
                    ->orWhereHas('kit');
            })
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                        ]);
                },
                'kits' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices'
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getSelectProductsAllWarehouses($warehouse_id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->where(function ($query) use ($warehouse_id) {
                //product
                $query->where(function ($qk) use ($warehouse_id) {
                    $qk->where('is_actual', Yesno::YES)
                        ->whereNull('group_id')
                        ->whereHas('warehouses', function ($q) use ($warehouse_id) {
                            $q->where('warehouse_id', $warehouse_id)->where('balance', '>', 0);
                        });
                    //groups
                })->orWhere(function ($qp) {
                    $qp->whereHas('groups', function ($q) {
                        $q->where('archive', Yesno::NO)
                            ->where('is_actual', Yesno::YES)
                            ->whereHas('warehouses', function ($q) {
                                $q->where('balance', '>', 0);
                            });
                    });
                    //kits
                })->orWhere(function ($qp) {
                    $qp->whereHas('kit')
                        ->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES);

                });
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'groups' => function ($q) use ($warehouse_id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('warehouses', function ($q) use ($warehouse_id) {
                            $q->where('balance', '>', 0)->where('warehouse_id', $warehouse_id);
                        })
                        ->with([
                            'product',
                            'prices'
                        ]);
                },
                'kits' => function ($q) use ($warehouse_id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) use ($warehouse_id) {
                                $q->where('archive', Yesno::NO)->where('warehouse_id', $warehouse_id)->where('balance', '>', 0);
                            },
                            'prices'
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getSelectServicesAll()
    {
        $items = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('service')
            ->with([
                'service' => function ($q) {
                    $q->with(['units']);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectServicesTable($items),
            'total_page' => $items->lastPage(),
            'total' => $items->total()
        ];
    }

    public function getServices($id)
    {
        $services = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('service')
            ->with([
                'service' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getServicesTable($services, $this->getPricesServiceHeader()),
            //'body'  => $products,
            'total_page' => $services->lastPage(),
            'total' => $services->total()
        ];
    }

    public function getNomenclatures($id)
    {
        $nomenclatures = $this->model()->withoutGlobalScope('archive_is_actual')->where('archive', Yesno::NO)
            ->whereIn('category_id', $id)
            ->where(function ($query) {
                $query->whereHas('product')
                    ->orWhereHas('service')
                    ->orWhereHas('kit');
            })
            ->where(function ($query) {
                $query->where(function ($qk) {
                    $qk->withoutGlobalScope('archive_is_actual')->where('is_actual', Yesno::NO)
                        ->whereNull('group_id');
                })->orWhere(function ($qp) {
                    $qp->withoutGlobalScope('archive_is_actual')->whereHas('groups', function ($q) {
                        $q->where('archive', Yesno::NO)->where('is_actual', Yesno::NO);
                    });
                });
            })
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'service' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'groups' => function ($q) {
                    $q->withoutGlobalScope('archive_is_actual')
                        ->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'kits' => function ($q) {
                    $q->withoutGlobalScope('archive_is_actual')
                        ->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::NO)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $price_header = collect($this->getPricesHeader());
        $price_service_header = collect($this->getPricesHeader());
        $prices = $price_header->merge($price_service_header);

        return $data = [
            'body' => $this->getNomenclaturesTable($nomenclatures, $prices->unique()),
            'total_page' => $nomenclatures->lastPage(),
            'total' => $nomenclatures->total()
        ];
    }

    public function getPricesHeader()
    {
        return ProductsHeader::select('value')->where('value', 'LIKE', "%price_%")->get();
    }

    public function getPricesServiceHeader()
    {
        return ServicesHeader::select('value')->where('value', 'LIKE', "%price_%")->get();
    }

    public function getProductsAll()
    {
        $products = $this->model()
            //->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with('units');
                },
                'prices',
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getProductsTable($products, $this->getPricesHeader()),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getKitsAll()
    {
        $kits = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('kit')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries', 'suppliers', 'weights']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'kits' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])
            ->orderBy('order', 'ASC')
            ->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getKitsTable($kits, $this->getPricesHeader()),
            //'body'  => $products,
            'total_page' => $kits->lastPage(),
            'total' => $kits->total()
        ];
    }

    public function getNomenclaturesAll()
    {
        $nomenclatures = $this->model()->withoutGlobalScope('archive_is_actual')->where('archive', Yesno::NO)
            ->where(function ($query) {
                $query->whereHas('product')
                    ->orWhereHas('service')
                    ->orWhereHas('kit');
            })
            ->where(function ($query) {
                $query->where(function ($qk) {
                    $qk->withoutGlobalScope('archive_is_actual')->where('is_actual', Yesno::NO)
                        ->whereNull('group_id');
                })->orWhere(function ($qp) {
                    $qp->withoutGlobalScope('archive_is_actual')->whereHas('groups', function ($q) {
                        $q->withoutGlobalScope('archive_is_actual')->where('archive', Yesno::NO)->where('is_actual', Yesno::NO);
                    });
                });
            })
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'service' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
//                'kit' => function ($q) {
//                    $q->with(['units', 'suppliers']);
//                },
                'prices',
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                },
                'groups' => function ($q) {
                    $q->withoutGlobalScope('archive_is_actual')->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::NO)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
                'kits' => function ($q) {
                    $q->withoutGlobalScope('archive_is_actual')->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::NO)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices',
                            'basketOrders' => function ($q) {
                                $q->where('user_id', $this->request->user_id);
                            },
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $price_header = collect($this->getPricesHeader());
        $price_service_header = collect($this->getPricesHeader());
        $prices = $price_header->merge($price_service_header);

        return $data = [
            'body' => $this->getNomenclaturesTable($nomenclatures, $prices->unique()),
            'total_page' => $nomenclatures->lastPage(),
            'total' => $nomenclatures->total()
        ];
    }

    public function getServicesAll()
    {
        $services = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('service')
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->with([
                'service' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'prices',
                'basketOrders' => function ($q) {
                    $q->where('user_id', $this->request->user_id);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getServicesTable($services, $this->getPricesServiceHeader()),
            'total_page' => $services->lastPage(),
            'total' => $services->total()
        ];
    }

    public function getProduct($id)
    {
        return Product::find($id)->with(['units'])->first();
    }

    public function changeMinPriceNomenclature($id)
    {
        $get_nomenclature = $this->model()->withoutGlobalScope('archive_is_actual')->where('id', $id)->with(['product', 'service', 'kit'])->first();

        if(isset($get_nomenclature->product)){
            return $get_nomenclature->product()->update([
                'min_price' => $this->request->value
            ]);
        }elseif (isset($get_nomenclature->service)){
            return $get_nomenclature->service()->update([
                'min_price' => $this->request->value
            ]);
        }elseif (isset($get_nomenclature->kit)){
            return $get_nomenclature->kit()->update([
                'min_price' => $this->request->value
            ]);
        }
    }

    public function changeMinPriceNomenclatureSetting($id, $value)
    {
        return $this->findNomenclature($id)->product()->update([
            'min_price' => $value
        ]);
    }

    public function getNomenclaturesForMove()
    {
        return $this->model()->whereIn('id', $this->request->data)->with([
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('property');
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            }
        ])->get();
    }

    public function getNomenclature($id)
    {
        $product = $this->model()->where('id', $id)
            ->withoutGlobalScope('archive_is_actual')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'weights', 'suppliers']);
                },
                'propertyValue' => function ($qpv) {
                    $qpv->whereHas('property', function ($qp) {
                        $qp->where('archive', 0)->orderBy('order', 'ASC');
                    })->with('property');
                },
                'categories' => function ($c) {
                    $c->where('archive', 0)->orderBy('order', 'ASC');
                },
                'prices',
                'category' => function ($ct) {
                    $ct->with([
                        'parent' => function ($q) {
                            $q->where('archive', '0')->orderBy('order', 'ASC');
                        }
                    ]);
                },
                'characteristicValue' => function ($cv) {
                    $cv->whereHas('characteristic', function ($qp) {
                        $qp->where('archive', 0)->orderBy('order', 'ASC');
                    })->with('characteristic');
                },
                'characteristicColorValue' => function ($ccv) {
                    $ccv->whereHas('characteristic', function ($qp) {
                        $qp->where('archive', 0)->orderBy('order', 'ASC');
                    })->with('characteristic');
                },
                'characteristic' => function ($ccv) {
                    $ccv->with(['characteristicValue', 'characteristicColorValue']);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
                'related' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
                'files',
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            },
                            'prices'
                        ]);
                },
            ])->first();

        $nomenclatures = $this->getProductUpdate($product, $this->getPricesHeader());
        $related_nomenclatures = $this->tableRelatedNomenclaturesInNomenclature($product->related, $this->getPricesHeader());

        if (isset($related_nomenclatures['arr'])) {
            foreach ($related_nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        $items_related = [
            'headers' => TitleJson::get(tableRelatedNomenclaturesInNomenclatureHeader::get(), $this->lang),
            'body' => $nomenclature_array
        ];

        $history_price = [
            'headers' => TitleJson::get(NomenclaturePriceTableHeader::get(), $this->lang),
            'body' => $this->getPricesTable($product)
        ];

        $nomenclatures['related'] = $items_related;
        $nomenclatures['history_price'] = $history_price;

        return $nomenclatures;
    }

    public function getNomenclatureService($id)
    {
        $service = $this->model()->where('id', $id)->with([
            'service' => function ($q) {
                $q->with(['units', 'suppliers']);
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
        ])->first();

        return $this->getServiceUpdate($service, $this->getPricesServiceHeader());
    }

    public function getNomenclatureKit($id)
    {
        $product = $this->model()->where('id', $id)->with([
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('property');
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristic' => function ($ccv) {
                $ccv->with(['characteristicValue', 'characteristicColorValue']);
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
            'kit' => function ($q) {
                $q->with(['units', 'weights', 'suppliers']);
            },
            'kits' => function ($q) {
                $q->where('archive', Yesno::NO)
                    ->where('is_actual', Yesno::YES)
                    ->with([
                        'product',
                        'warehouses' => function ($q) {
                            $q->where('archive', Yesno::NO);
                        },
                        'prices'
                    ]);
            },
            'files'
        ])->first();

        $nomenclatures = $this->getKitUpdate($product, $this->getPricesHeader());
        return $nomenclatures;
    }


    public function getNomenclatureTable($id)
    {
        $product = $this->model()->where('id', $id)->with([
            'product' => function ($q) {
                $q->with(['units', 'weights']);
            },
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('property');
            },
            'categories' => function ($c) {
                $c->where('archive', Yesno::NO)->orderBy('order', 'ASC');
            },
            'prices' => function ($q) {
                $q->where('status', Yesno::YES);
            },
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                    }
                ]);
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
            'groups' => function ($q) {
                $q->where('archive', Yesno::NO)
                    ->where('is_actual', Yesno::YES)
                    ->whereHas('product')
                    ->with([
                        'product',
                        'warehouses' => function ($q) {
                            $q->where('archive', Yesno::NO);
                        },
                        'prices'
                    ]);
            },
        ])->first();
        //return $product;
        return $this->getProductTable($product, $this->getPricesHeader());
    }

    public function getNomenclatureServiceTable($id)
    {
        $service = $this->model()->where('id', $id)->with([
            'service' => function ($q) {
                $q->with(['units', 'suppliers']);
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
//            'groups' => function ($q) {
//                $q->where('archive', Yesno::NO)
//                    ->where('is_actual', Yesno::YES)
//                    ->whereHas('product')
//                    ->with([
//                        'product',
//                        'warehouses' => function ($q) {
//                            $q->where('archive', Yesno::NO);
//                        }
//                    ]);
//            },
        ])->first();

        return $this->getServiceTable($service, $this->getPricesServiceHeader());
    }

    public function getNomenclatureKitTable($id)
    {
        $kit = $this->model()->where('id', $id)->with([
            'kit' => function ($q) {
                $q->with(['units', 'suppliers']);
            },
            'kits' => function ($q) {
                $q->with([
                    'product'
                ])->where('archive', Yesno::NO);
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('property');
            },
        ])->first();

        return $this->getKitTable($kit, $this->getPricesHeader());
    }

    public function getNomenclatureProductId($id)
    {
        return Product::select('id')->where('nomenclature_id', $id)->first();
    }

    public function getNomenclatureOutHelper($id)
    {
        $product = $this->model()->where('id', $id)->with([
            'product' => function ($q) {
                $q->with(['units', 'weights']);
            },
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('property');
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
            'groups' => function ($q) {
                $q->where('archive', Yesno::NO)
                    ->where('is_actual', Yesno::YES)
                    ->whereHas('product')
                    ->with([
                        'product',
                        'warehouses' => function ($q) {
                            $q->where('archive', Yesno::NO);
                        }
                    ]);
            },
        ])->first();
        return $product;
        //return $this->getProductUpdate($product, $this->getPricesHeader());
    }

    public function getNomenclatureServiceOutHelper($id)
    {
        $product = $this->model()->where('id', $id)->with([
            'service' => function ($q) {
                $q->with(['units', 'suppliers']);
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            }
        ])->first();
        return $product;
        //return $this->getProductUpdate($product, $this->getPricesHeader());
    }

    public function getNomenclatureKitOutHelper($id)
    {
        $product = $this->model()->where('id', $id)->with([
            'propertyValue' => function ($qpv) {
                $qpv->whereHas('property', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('property');
            },
            'categories' => function ($c) {
                $c->where('archive', 0)->orderBy('order', 'ASC');
            },
            'prices',
            'category' => function ($ct) {
                $ct->with([
                    'parent' => function ($q) {
                        $q->where('archive', '0')->orderBy('order', 'ASC');
                    }
                ]);
            },
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', 0)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
            'kit' => function ($q) {
                $q->with(['units', 'weights', 'suppliers']);
            },
            'kits' => function ($q) {
                $q->where('archive', Yesno::NO)
                    ->where('is_actual', Yesno::YES)
                    ->with([
                        'product',
                    ]);
            },
        ])->first();
        return $product;
        //return $this->getProductUpdate($product, $this->getPricesHeader());
    }

    public function getServicesHeader()
    {
        return TitleJson::get(ServicesHeader::get(), $this->lang);
    }

    public function getServiceHeader($id)
    {
        return ServicesHeader::find($id);
    }

    public function getSelectProductsHeader()
    {
        return TitleJson::get(SelectProductsHeader::get(), $this->lang);
    }

    public function getSelectServicesHeader()
    {
        return TitleJson::get(SelectServicesHeader::get(), $this->lang);
    }

    public function getKitHeader($id)
    {
        return KitsHeader::find($id);
    }

    public function getKitsHeader()
    {
        return TitleJson::get(KitsHeader::get(), $this->lang);
    }

    public function getProductHeader($id)
    {
        return ProductsHeader::find($id);
    }

    public function getProductsHeader()
    {
        return TitleJson::get(ProductsHeader::get(), $this->lang);
    }

    public function getRelatedProductHeader($id)
    {
        return RelatedProductsHeader::find($id);
    }

    public function getSelectRelatedProductHeader($id)
    {
        return SelectRelatedProductsHeader::find($id);
    }

    public function getRelatedProductsHeader()
    {
        return TitleJson::get(RelatedProductsHeader::get(), $this->lang);
    }

    public function getProductInRelatedHeader($id)
    {
        return ProductsInRelatedHeader::find($id);
    }

    public function getProductsInRelatedHeader()
    {
        return TitleJson::get(ProductsInRelatedHeader::get(), $this->lang);
    }

    public function getNomenclatureHeader($id)
    {
        return NomenclaturesHeader::find($id);
    }

    public function getNomenclaturesHeader()
    {
        return TitleJson::get(NomenclaturesHeader::get(), $this->lang);
    }

    public function getTableGroupsNomenclaturesHeader()
    {
        return TitleJson::get(TableGroupsNomenclaturesHeader::get(), $this->lang);
    }

    public function incrementNomenclature()
    {
        return $this->model()->increment('order');
    }

    public function createProduct($request)
    {
        $nomenclature = $this->model()->create([
            'category_id' => $request['category_id'],
            'is_visible' => $request['is_visible'],
            'order' => 0
        ]);

        //dd($request);
        $product = Product::create([
            'short_title' => $request['short_title'],
            'convert_id' => str_pad($nomenclature['id'], 8, '0', STR_PAD_LEFT),
            'dock_title' => $request['dock_title'],
            'desc' => $request['desc'] ?? '',
            'sku' => $request['sku'] ?? null,
            'min_price' => $request['min_price'] ?? null,
            'weight' => $request['weight'] ?? null,
            'volume' => $request['volume'] ?? null,
            'lower_limit' => $request['lower_limit'] ?? 0,
            //'general_count' => $request['general_count'] ?? 0,
            //'reserve' => $request['reserve'] ?? 0,
            'barcode_supplier' => $request['barcode_supplier'] ?? null,
            'barcode' => $request['barcode'] ?? null,
            'packing' => $request['packing'] ?? null,
            'identifier_fullness' => $request['identifier_fullness'] ?? null,
            'identifier_successful' => $request['identifier_successful'] ?? null,
            //'model' => $request['model'] ?? null,
            //'brand' => $request['brand'] ?? null,
            'nomenclature_id' => $nomenclature['id'],
            'is_groups' => false,
            'unit_id' => $request['unit_id'] ?? null,
            'supplier_id' => $request['supplier_id'] ?? null,
            'country_id' => $request['country_id'] ?? null,
            'weight_id' => $request['weight_id'] ?? null,
            'volume_id' => $request['volume_id'] ?? null,
        ]);


        if (isset($request['characteristic_value'])) {
            foreach ($request['characteristic_value'] as $key => $characteristic) {
                $nomenclature->characteristicValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic_color_value'])) {
            foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature->characteristicColorValue()->attach($characteristic['id']);
            }
        }

//        if (isset($request['base_characteristic_value'])) {
//
//            if (isset($request['base_characteristic_value']['is_color']) && $request['base_characteristic_value']['is_color'] == true) {
//                foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
//                    $nomenclature->characteristicColorValue()->attach($value['id'], ['is_base' => 1]);
//                }
//            } else {
//                foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
//                    $nomenclature->characteristicValue()->attach($value['id'], ['is_base' => 1]);
//                }
//            }
//        }

        if (isset($request['property_value'])) {
            foreach ($request['property_value'] as $key => $property) {
                $nomenclature->propertyValue()->attach($property['id']);
            }
        }

        if (isset($request['categories'])) {
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            foreach ($request['prices'] as $key => $prices) {
                $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? null, $prices['value'], $request['supplier_id'] ?? null);

//                $nomenclature->prices()->attach($prices['id'], [
//                    'value' => $prices['value'],
//                    'unit_id' => $request['unit_id'] ?? null,
//                    'supplier_id' => $request['supplier_id'] ?? null,
//                    'date' => $nomenclature->created_at
//                ]);
            }
        }

        if (isset($request['related'])) {
            foreach ($request['related'] as $key => $value) {
                $nomenclature_related[$key]['related_id'] = $value['id'];
            }

            $nomenclature->related()->sync($nomenclature_related);
        }

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $value) {
                $files[$key]['file_id'] = $value['id'];
            }
            $nomenclature->files()->sync($files);
        }

        return $this->getNomenclatureTable($nomenclature->id);
    }

    public function createKit($request)
    {
        $nomenclature = $this->model()->create([
            'category_id' => $request['category_id'],
            'is_visible' => $request['is_visible'],
            'order' => 0
        ]);

        $kit = Kit::create([
            'short_title' => $request['short_title'],
            'convert_id' => str_pad($nomenclature['id'], 8, '0', STR_PAD_LEFT),
            'dock_title' => $request['dock_title'],
            'desc' => $request['desc'] ?? '',
            'sku' => $request['sku'] ?? null,
            'min_price' => $request['min_price'] ?? null,
            'weight' => $request['weight'] ?? null,
            'volume' => $request['volume'] ?? null,
            'lower_limit' => $request['lower_limit'] ?? 0,
            //'general_count' => $request['general_count'] ?? 0,
            //'reserve' => $request['reserve'] ?? 0,
            //'barcode_supplier' => $request['barcode_supplier'] ?? null,
            //'barcode' => $request['barcode'] ?? null,
            'packing' => $request['packing'] ?? null,
            'identifier_fullness' => $request['identifier_fullness'] ?? null,
            'identifier_successful' => $request['identifier_successful'] ?? null,
            //'model' => $request['model'] ?? null,
            //'brand' => $request['brand'] ?? null,
            'nomenclature_id' => $nomenclature['id'],
            //'is_groups' => false,
            'unit_id' => $request['unit_id'] ?? null,
            'supplier_id' => $request['supplier_id'] ?? null,
            'country_id' => $request['country_id'] ?? null,
            'weight_id' => $request['weight_id'] ?? null,
            //'volume_id' => $request['volume_id'] ?? null,

            //'organization_id' => $request['organization_id'] ?? null
        ]);

        if (isset($request['characteristic_value'])) {
            foreach ($request['characteristic_value'] as $key => $characteristic) {
                $nomenclature->characteristicValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic_color_value'])) {
            foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature->characteristicColorValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['property_value'])) {
            foreach ($request['property_value'] as $key => $property) {
                $nomenclature->propertyValue()->attach($property['id']);
            }
        }

        if (isset($request['categories'])) {
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            foreach ($request['prices'] as $key => $prices) {
                $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? null, $prices['value'], $request['supplier_id'] ?? null);
            }
        }

        if (isset($request['kits'])) {
            foreach ($request['kits'] as $key => $value) {
                $nomenclature_kit[$key]['kit_id'] = $value['id'];
                $nomenclature_kit[$key]['count'] = $value['count'];
            }
            $nomenclature->kits()->attach($nomenclature_kit);
        }

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $value) {
                $files[$key]['file_id'] = $value['id'];
            }
            $nomenclature->files()->sync($files);
        }

        return $this->getNomenclatureKitTable($nomenclature->id);
    }

    public function createService($request)
    {
        $nomenclature = $this->model()->create([
            'category_id' => $request['category_id'],
            'is_visible' => $request['is_visible'],
            'order' => 0
        ]);

        $service = Service::create([
            'short_title' => $request['short_title'],
            'dock_title' => $request['dock_title'],
            'desc' => $request['desc'] ?? '',
            'sku' => $request['sku'] ?? null,
            'min_price' => $request['min_price'] ?? null,
            'nomenclature_id' => $nomenclature['id'],
            'is_groups' => false,
            'unit_id' => $request['unit_id'] ?? null,
            'supplier_id' => $request['supplier_id'] ?? null,
            //'organization_id' => $request['organization_id'] ?? null
        ]);

        if (isset($request['categories'])) {
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            foreach ($request['prices'] as $key => $prices) {
                $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? null, $prices['value'], $request['supplier_id'] ?? null);

//                $nomenclature->prices()->attach($prices['id'], [
//                    'value' => $prices['value'],
//                    'unit_id' => $request['unit_id'] ?? null,
//                    'supplier_id' => $request['supplier_id'] ?? null,
//                    'date' => $nomenclature->created_at ?? null
//                ]);
            }
        }

        return $this->getNomenclatureServiceTable($nomenclature->id);
    }

    public function createGroupProduct($request)
    {
        $nomenclature = $this->model()->create([
            'category_id' => $request['category_id'],
            'is_visible' => $request['is_visible'],
            'order' => 0
        ]);

        $product = Product::create([
            'short_title' => $request['short_title'],
            'convert_id' => str_pad($nomenclature['id'], 8, '0', STR_PAD_LEFT),
            'dock_title' => $request['dock_title'],
            'desc' => $request['desc'] ?? '',
            'sku' => $request['sku'] ?? null,
            'min_price' => $request['min_price'] ?? null,
            'weight' => $request['weight'] ?? null,
            'volume' => $request['volume'] ?? null,
            'lower_limit' => $request['lower_limit'] ?? 0,
            //'general_count' => $request['general_count'] ?? 0,
            //'reserve' => $request['reserve'] ?? 0,
            'barcode_supplier' => $request['barcode_supplier'] ?? null,
            'barcode' => $request['barcode'] ?? null,
            'packing' => $request['packing'] ?? null,
            'identifier_fullness' => $request['identifier_fullness'] ?? null,
            'identifier_successful' => $request['identifier_successful'] ?? null,
            //'model' => $request['model'] ?? null,
            //'brand' => $request['brand'] ?? null,
            'nomenclature_id' => $nomenclature['id'],
            'is_groups' => true,
            'unit_id' => $request['unit_id'] ?? null,
            'supplier_id' => $request['supplier_id'] ?? null,
            'country_id' => $request['country_id'] ?? null,
            'weight_id' => $request['weight_id'] ?? null,
            'volume_id' => $request['volume_id'] ?? null,
        ]);

        if (isset($request['characteristic_value'])) {
            foreach ($request['characteristic_value'] as $key => $characteristic) {
                $nomenclature->characteristicValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic_color_value'])) {
            foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature->characteristicColorValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['base_characteristic_value'])) {

            if (isset($request['base_characteristic_value']['is_color']) && $request['base_characteristic_value']['is_color'] == true) {
                foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
                    $nomenclature->characteristicColorValue()->attach($value['id'], ['is_base' => 1]);
                }
            } else {
                foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
                    $nomenclature->characteristicValue()->attach($value['id'], ['is_base' => 1]);
                }
            }
        }

        if (isset($request['base_characteristic'])) {
            foreach ($request['base_characteristic']['ids'] as $key => $value) {
                $nomenclature->characteristic()->attach($value['id'], ['is_base' => 1]);
            }
        }

        if (isset($request['characteristic'])) {
            foreach ($request['characteristic'] as $key => $characteristic) {
                $nomenclature->characteristic()->attach($characteristic['id']);
            }
        }

        if (isset($request['property_value'])) {
            foreach ($request['property_value'] as $key => $property) {
                $nomenclature->propertyValue()->attach($property['id']);
            }
        }

        if (isset($request['categories'])) {
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            foreach ($request['prices'] as $key => $prices) {
                $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? null, $prices['value'], $request['supplier_id'] ?? null);
            }
        }

        if (isset($request['related'])) {
            foreach ($request['related'] as $key => $value) {
                $nomenclature_related[$key]['related_id'] = $value['id'];
            }
            $nomenclature->related()->sync($nomenclature_related);
        }

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $value) {
                $files[$key]['file_id'] = $value['id'];
            }
            $nomenclature->files()->sync($files);
        }

        return $this->getNomenclatureTable($nomenclature->id);
    }

    public function checkTitleSkuProduct($title, $sku)
    {
        if (isset($sku)) {
            return Product::where('short_title', $title)->where('sku', $sku)->first();
        } else {
            return Product::where('short_title', $title)->first();
        }
    }

    public function checkTitleSkuService($title, $sku)
    {
        if (isset($sku)) {
            return Service::where('short_title', $title)->where('sku', $sku)->first();
        } else {
            return Service::where('short_title', $title)->first();
        }
    }

    public function createGroupsNomenclature($request, $id, $count)
    {
        $nomenclature = collect($this->getNomenclatureOutHelper($id));
//        $i = $count;
        foreach ($request['products'] as $key => $value) {
            $count++;
            $nomenclature_create = $this->model()->create([
                'category_id' => $nomenclature['category_id'],
                'order' => $key,
                'group_id' => $id
            ]);

            $product = Product::create([
                'short_title' => $value['short_title'],
                'convert_id' => str_pad($nomenclature['id'], 8, '0', STR_PAD_LEFT) . '/' . $count,
                'dock_title' => $nomenclature['product']['dock_title'],
                'desc' => $nomenclature['product']['desc'] ?? '',
                'sku' => $value['sku'] ?? null,
                'min_price' => $value['min_price'] ?? null,
                'weight' => $nomenclature['product']['weight'] ?? null,
                'volume' => $nomenclature['product']['volume'] ?? null,
                'lower_limit' => $nomenclature['product']['lower_limit'] ?? 0,
                'barcode_supplier' => $nomenclature['product']['barcode_supplier'] ?? null,
                'barcode' => $nomenclature['product']['barcode'] ?? null,
                'packing' => $nomenclature['product']['packing'] ?? null,
                'is_groups' => false,
                'identifier_fullness' => $nomenclature['product']['identifier_fullness'] ?? null,
                'identifier_successful' => $nomenclature['product']['identifier_successful'] ?? null,
                'nomenclature_id' => $nomenclature_create['id'],
                'unit_id' => $nomenclature['product']['unit_id'],
                'supplier_id' => $nomenclature['product']['supplier_id'],
                'country_id' => $nomenclature['product']['country_id'],
                'weight_id' => $nomenclature['product']['weight_id'],
                'volume_id' => $nomenclature['product']['volume_id'],

            ]);

            if (isset($value['characteristic_value'])) {
                foreach ($value['characteristic_value'] as $k => $characteristic) {
                    $nomenclature_create->characteristicValue()->attach($characteristic['id']);
                }
            }

            if (isset($value['characteristic_color_value'])) {
                //foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature_create->characteristicColorValue()->attach($value['characteristic_color_value']['id']);
                //}
            }

            if (isset($value['base_characteristic_value'])) {

                if (isset($value['base_characteristic_value']['is_color']) && $value['base_characteristic_value']['is_color'] == true) {
                    $nomenclature_create->characteristicColorValue()->attach($value['base_characteristic_value']['id'], ['is_base' => 1]);
                } else {
                    $nomenclature_create->characteristicValue()->attach($value['base_characteristic_value']['id'], ['is_base' => 1]);
                }
            }

            if (isset($nomenclature['property_value'])) {
                foreach ($nomenclature['property_value'] as $k => $property) {
                    $nomenclature_create->propertyValue()->attach($property['id']);
                }
            }

            if (isset($nomenclature['categories'])) {
                foreach ($nomenclature['categories'] as $k => $category) {
                    $nomenclature_create->categories()->attach($category['id']);
                }
            }

            if (isset($nomenclature['prices'])) {
                foreach ($nomenclature['prices'] as $k => $prices) {
                    $nomenclature_create->prices()->attach($prices['id'], [
                        'value' => $prices['pivot']['value'],
                        'unit_id' => $nomenclature['product']['unit_id'] ?? null,
                        'supplier_id' => $nomenclature['product']['supplier_id'] ?? null,
                        'date' => $nomenclature_create->created_at
                    ]);
                }
            }

            if (isset($nomenclature['related'])) {
                foreach ($request['related'] as $k => $v) {
                    $nomenclature_related[$k]['related_id'] = $v['id'];
                }
                $nomenclature_create->related()->sync($nomenclature_related);
            }


            if (isset($request['files'])) {
                foreach ($request['files'] as $k => $v) {
                    $files[$key]['file_id'] = $v['id'];
                }
                $nomenclature_create->files()->sync($files);
            }
        }

        return $this->getNomenclatureTable($id);
    }

    public function addNameBackup($title)
    {
        return $title . '-backup-' . Carbon::now();
    }

    public function toVisibilityNomenclature($nomenclatures)
    {
        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->update([
            'is_visible' => '1',
        ]);

        //return $this->getCategoryWithChildren($id);
    }

    public function outVisibilityNomenclature($nomenclatures)
    {
        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->update([
            'is_visible' => '0',
        ]);

        //return $this->getCategoryWithChildren($id);
    }

    public function toActual($nomenclatures)
    {
        //dd($nomenclatures);
        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->update([
            'is_actual' => Yesno::YES
        ]);

        //return $this->getCategoryWithChildren($id); CharacteristicColorValue::get()
    }

    public function toArchive($nomenclatures)
    {
        $get_nomenclatures = $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->with(['product', 'service', 'kit'])->get();

        foreach ($get_nomenclatures as $nomenclature) {

            if (isset($nomenclature->product)) {
                $short_title = $this->addNameBackup($nomenclature->product->short_title);
                $sku = $nomenclature->product->sku ? $this->addNameBackup($nomenclature->product->sku) : null;
                $check = $this->checkTitleSkuProduct($short_title, $sku);
                if (is_null($check)) {
                    $nomenclature->product->update([
                        'short_title' => $short_title,
                        'sku' => $sku
                    ]);

                    $nomenclature->update([
                        'archive' => 1
                    ]);
                }
            } elseif (isset($nomenclature->service)) {

                $short_title = $this->addNameBackup($nomenclature->service->short_title);
                $sku = $nomenclature->service->sku ? $this->addNameBackup($nomenclature->service->sku) : null;
                $check = $this->checkTitleSkuService($short_title, $sku);
                if (is_null($check)) {
                    $nomenclature->service->update([
                        'short_title' => $short_title,
                        'sku' => $sku
                    ]);

                    $nomenclature->update([
                        'archive' => 1
                    ]);
                }
            } elseif (isset($nomenclature->kit)) {

                $short_title = $this->addNameBackup($nomenclature->kit->short_title);
                $sku = $nomenclature->kit->sku ? $this->addNameBackup($nomenclature->kit->sku) : null;
                $check = $this->checkTitleSkuService($short_title, $sku);
                if (is_null($check)) {
                    $nomenclature->kit->update([
                        'short_title' => $short_title,
                        'sku' => $sku
                    ]);

                    $nomenclature->update([
                        'archive' => 1
                    ]);
                }
            }

        }
        return $get_nomenclatures;
    }

    public function outActual($nomenclatures)
    {
        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->update([
            'is_actual' => '0'
        ]);

        //return $this->getCategoryWithChildren($id);
    }

    public function outArchive($nomenclatures)
    {
        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $nomenclatures)->update([
            'archive' => '0'
        ]);
    }

    public function moveProducts($id)
    {

        return $this->model()->withoutGlobalScope('archive_is_actual')->whereIn('id', $this->request->data)->update([
            'category_id' => $id
        ]);
    }

    public function changePriceNomenclature($request)
    {
        //$price = NomenclaturePrice::where('id', $request['id'])->first();
        $price = PriceRepository::getPrice($request['id']);

        $nomenclature_min_price = $this->getNomenclatureProductService($price->nomenclature_id);

        if (isset($nomenclature_min_price->product->min_price)) {
            $min_price = $nomenclature_min_price->product->min_price;
        } elseif (isset($nomenclature_min_price->service->min_price)) {
            $min_price = $nomenclature_min_price->service->min_price;
        } elseif (isset($nomenclature_min_price->kit->min_price)) {
            $min_price = $nomenclature_min_price->kit->min_price;
        } else {
            $min_price = null;
        }

        if (isset($min_price) && ($price->price_id != 1)) {
            if ($min_price <= $request['value']) {
                $price_data = $this->createNomenclaturesPrice($price['nomenclature_id'], $price['price_id'], $price['unit_id'], $request['value']);
            } else {
                return $data = [
                    'message' => __('validation.custom.min_price') . ' ' . $min_price,
                    'status' => 'error'
                ];
            }
        } else {
            $price_data = $this->createNomenclaturesPrice($price['nomenclature_id'], $price['price_id'], $price['unit_id'], $request['value']);
        }

        return $data = [
            'message' => 'Successful operation',
            'status' => 'success',
            'price' => $price_data
        ];
    }

    public function changePriceNomenclatureHistory($request)
    {
        $price = PriceRepository::getPrice($request['id']);
        $current_price = PriceRepository::getCurrentPrice($price->nomenclature_id, $price->price_id);

        if ($current_price->id == $price->id) {
            $nomenclature_min_price = $this->getNomenclatureProductService($price->nomenclature_id);
            if (isset($nomenclature_min_price->product->min_price)) {
                $min_price = $nomenclature_min_price->product->min_price;
            } elseif (isset($nomenclature_min_price->service->min_price)) {
                $min_price = $nomenclature_min_price->service->min_price;
            } elseif (isset($nomenclature_min_price->kit->min_price)) {
                $min_price = $nomenclature_min_price->kit->min_price;
            } else {
                $min_price = null;
            }

            if (isset($min_price) && ($price->price_id != 1)) {
                if ($min_price <= $request['value']) {
                    $price_data = PriceRepository::editPrice($request['id'], $request['value']);
                    //$price_data = $this->createNomenclaturesPrice($price['nomenclature_id'], $price['price_id'], $price['unit_id'], $request['value']);
                } else {
                    return $data = [
                        'message' => __('validation.custom.min_price') . ' ' . $min_price,
                        'status' => 'error'
                    ];
                }
            } else {
                $price_data = PriceRepository::editPrice($request['id'], $request['value']);
                //$price_data = $this->createNomenclaturesPrice($price['nomenclature_id'], $price['price_id'], $price['unit_id'], $request['value']);
            }
        } else {
            $price_data = PriceRepository::editPrice($request['id'], $request['value']);
        }

        return $data = [
            'message' => 'Successful operation',
            'status' => 'success',
            'price' => $price_data
        ];
    }

    public function choosePriceNomenclature($request)
    {
        $price_explode = explode('_', $request['name_price']);
        $price_id = $price_explode[1];

        $nomenclature_min_price = $this->getNomenclatureProductService($request['nomenclature_id']);

        if (isset($nomenclature_min_price->product->unit_id)) {
            $unit_id = $nomenclature_min_price->product->unit_id;
        } elseif (isset($nomenclature_min_price->service->min_price)) {
            $unit_id = $nomenclature_min_price->service->unit_id;
        } else {
            $unit_id = null;
        }

        if ((isset($nomenclature_min_price->product->min_price) || isset($nomenclature_min_price->service->min_price)) && ($price_id != 1)) {

            if ((isset($nomenclature_min_price->product->min_price) && $nomenclature_min_price->product->min_price <= $request['value'])
                || (isset($nomenclature_min_price->service->min_price) && $nomenclature_min_price->service->min_price <= $request['value'])) {

                $price_data = $this->createNomenclaturesPrice($request['nomenclature_id'], $price_id, $unit_id, $request['value']);

            } else {
                return $data = [
                    'message' => __('validation.custom.min_price') . ' ' . $nomenclature_min_price->product->min_price ?? $nomenclature_min_price->service->min_price,
                    'status' => 'error'
                ];
            }
        } else {
            $price_data = $this->createNomenclaturesPrice($request['nomenclature_id'], $price_id, $unit_id, $request['value']);
        }

        return $data = [
            'message' => 'Successful operation',
            'status' => 'success',
            'price' => $price_data
        ];
    }

//    public function updateNomenclaturesPrice($request, $price)
//    {
//        NomenclaturePrice::where('id', $request['id'])->update([
//            'value' => $request['value']
//        ]);
//
//        $nomenclature = $this->model()->where('id', $price->nomenclature_id)->whereHas('groups')->with('groups')->first();
//
//        if (isset($nomenclature)) {
//            foreach ($nomenclature->groups as $value) {
//                NomenclaturePrice::where('nomenclature_id', $value->id)->update([
//                    'value' => $request['value']
//                ]);
//            }
//        }
//
//        return $nomenclature;
//    }

    public function createNomenclaturesPrice($nomenclature_id, $price_id, $unit_id, $value, $suplier_id = null)
    {
        $date = Carbon::now()->toDateString();

        $price = PriceRepository::updateOrCreateNomenclaturePrice($nomenclature_id, $price_id, $unit_id, $date, $value, $suplier_id);
        $nomenclature = $this->model()->where('id', $nomenclature_id)->whereHas('groups')->with('groups')->first();

        if (isset($nomenclature)) {
            foreach ($nomenclature->groups as $value) {
                PriceRepository::updateOrCreateNomenclaturePrice($value['id'], $price_id, $unit_id, $date, $value, $suplier_id);
            }
        }

        return $price;
    }

    public function getNomenclatureProductService($id)
    {
        return $this->model()->where('id', $id)->with('product', 'service', 'kit')->first();
    }

    public function findNomenclature($id)
    {
        return $this->model()->find($id);
    }


    public function updateProductsHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getProductHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateServicesHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getServiceHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateKitsHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getKitHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateNomenclaturesHeader()
    {

        foreach ($this->request->data as $item) {
            $this->getNomenclatureHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateRelatedProductsHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getRelatedProductHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateSelectRelatedProductsHeader()
    {
        foreach ($this->request->data as $item) {
            $this->getSelectRelatedProductHeader($item['id'])->update([
                'order' => $item['order'],
                'is_visible' => $item['is_visible'],
            ]);
        }
    }

    public function updateProduct($request, $id)
    {
        $nomenclature = $this->getNomenclatureOutHelper($id);

        $nomenclature->update([
            'category_id' => $request['category_id'] ?? $nomenclature->category_id
        ]);
        $nomenclature->product()->update([
            'short_title' => $request['short_title'] ?? $nomenclature->product->short_title,
            'dock_title' => $request['dock_title'] ?? $nomenclature->product->dock_title,
            'desc' => $request['desc'] ?? $nomenclature->product->desc,
            'sku' => $request['sku'] ?? $nomenclature->product->sku,
            'lower_limit' => $request['lower_limit'] ?? $nomenclature->product->lower_limit,
            'min_price' => $request['min_price'] ?? $nomenclature->product->min_price,
            'weight' => $request['weight'] ?? $nomenclature->product->weight,
            'volume' => $request['volume'] ?? $nomenclature->product->volume,
            //'general_count' => $request['general_count'] ?? $nomenclature->product->general_count,
            //'reserve' => $request['reserve'] ?? $nomenclature->product->reserve,
            'barcode_supplier' => $request['barcode_supplier'] ?? $nomenclature->product->barcode_supplier,
            'barcode' => $request['barcode'] ?? $nomenclature->product->barcode,
            'packing' => $request['packing'] ?? $nomenclature->product->packing,
            //'is_groups' => false,
            'identifier_fullness' => $request['identifier_fullness'] ?? $nomenclature->product->identifier_fullness,
            'identifier_successful' => $request['identifier_successful'] ?? $nomenclature->product->identifier_successful,
            //'brand' => $request['brand'] ?? $nomenclature->product->brand,
            //'model' => $request['model'] ?? $nomenclature->product->model,
            //'nomenclature_id' => $nomenclature['id'],
            'unit_id' => $request['unit_id'] ?? $nomenclature->product->unit_id,
            //'manufacturer_id' => $request['manufacturer_id'],
            'supplier_id' => $request['supplier_id'] ?? $nomenclature->product->supplier_id,
            'country_id' => $request['country_id'] ?? $nomenclature->product->country_id,
            'weight_id' => $request['weight_id'] ?? $nomenclature->product->weight_id,
            //'volume_id' => $request['volume_id'] ?? $nomenclature->product->volume_id,
        ]);

//        if (isset($value['base_characteristic_value'])) {
//            $nomenclature->characteristicValue()->detach();
//            $nomenclature->characteristicColorValue()->detach();
//            if (isset($request['base_characteristic_value'])) {
//
//                if (isset($request['base_characteristic_value']['is_color']) && $request['base_characteristic_value']['is_color'] == true) {
//                    foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
//                        $nomenclature->characteristicColorValue()->attach($value['id'], ['is_base' => 1]);
//                    }
//                } else {
//                    foreach ($request['base_characteristic_value']['ids'] as $key => $value) {
//                        $nomenclature->characteristicValue()->attach($value['id'], ['is_base' => 1]);
//                    }
//                }
//            }
//        }

        if (isset($request['characteristic_value'])) {
            $nomenclature->characteristicValue()->detach();
            foreach ($request['characteristic_value'] as $key => $characteristic) {
                $nomenclature->characteristicValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic_color_value'])) {
            $nomenclature->characteristicColorValue()->detach();
            foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature->characteristicColorValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic'])) {
            foreach ($request['characteristic'] as $key => $characteristic) {
                $nomenclature->characteristic()->attach($characteristic['id']);
            }
        }

        if (isset($request['property_value'])) {
            $nomenclature->propertyValue()->detach();
            foreach ($request['property_value'] as $key => $property) {
                $nomenclature->propertyValue()->attach($property['id']);
            }
        }

        if (isset($request['categories'])) {
            $nomenclature->categories()->detach();
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            //$nomenclature->prices()->detach();
            foreach ($request['prices'] as $key => $prices) {
                if (!isset($prices['type_price_id'])) {
                    $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? $nomenclature->product->unit_id, $prices['value'], $request['supplier_id'] ?? $nomenclature->product->supplier_id);
                }
//                $nomenclature->prices()->attach($prices['id'], [
//                    'value' => $prices['value'],
//                    'unit_id' => $request['unit_id'] ?? $nomenclature->product->unit_id,
//                    'supplier_id' => $request['supplier_id'] ?? $nomenclature->product->supplier_id,
//                    'date' => $nomenclature->created_at
//                ]);
            }
        }

        if (isset($request['related'])) {
            foreach ($request['related'] as $key => $value) {
                $nomenclature_related[$key]['related_id'] = $value['id'];
            }
            $nomenclature->related()->sync($nomenclature_related);
        }

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $value) {
                $files[$key]['file_id'] = $value['id'];
            }
            $nomenclature->files()->sync($files);
        }

        return $this->getNomenclatureTable($id);
    }

    public function updateService($request, $id)
    {
        $nomenclature = $this->getNomenclatureServiceOutHelper($id);
        $nomenclature->service()->update([
            'short_title' => $request['short_title'] ?? $nomenclature->service->short_title,
            'dock_title' => $request['dock_title'] ?? $nomenclature->service->dock_title,
            'desc' => $request['desc'] ?? $nomenclature->service->desc,
            'sku' => $request['sku'] ?? $nomenclature->service->sku,
            'min_price' => $request['min_price'] ?? $nomenclature->service->min_price,
            'unit_id' => $request['unit_id'] ?? $nomenclature->service->unit_id,
            'supplier_id' => $request['supplier_id'] ?? $nomenclature->service->supplier_id,
            //'organization_id' => $request['organization_id'] ?? $nomenclature->service->organization_id,
        ]);

        if (isset($request['categories'])) {
            $nomenclature->categories()->detach();
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            //$nomenclature->prices()->detach();
            foreach ($request['prices'] as $key => $prices) {
                $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? $nomenclature->service->unit_id, $prices['value'], $request['supplier_id'] ?? $nomenclature->service->supplier_id);
            }
        }

        return $this->getNomenclatureServiceTable($id);
    }

    public function updateKit($request, $id)
    {
        $nomenclature = $this->getNomenclatureKitOutHelper($id);

        $nomenclature->update([
            'category_id' => $request['category_id'] ?? $nomenclature->category_id
        ]);
        $nomenclature->kit()->update([
            'short_title' => $request['short_title'] ?? $nomenclature->kit->short_title,
            'dock_title' => $request['dock_title'] ?? $nomenclature->kit->dock_title,
            'desc' => $request['desc'] ?? $nomenclature->kit->desc,
            'sku' => $request['sku'] ?? $nomenclature->kit->sku,
            'lower_limit' => $request['lower_limit'] ?? $nomenclature->kit->lower_limit,
            'min_price' => $request['min_price'] ?? $nomenclature->kit->min_price,
            'weight' => $request['weight'] ?? $nomenclature->kit->weight,
            'volume' => $request['volume'] ?? $nomenclature->kit->volume,
            //'general_count' => $request['general_count'] ?? $nomenclature->product->general_count,
            //'reserve' => $request['reserve'] ?? $nomenclature->product->reserve,
            //'barcode_supplier' => $request['barcode_supplier'] ?? $nomenclature->kit->barcode_supplier,
            //'barcode' => $request['barcode'] ?? $nomenclature->kit->barcode,
            'packing' => $request['packing'] ?? $nomenclature->kit->packing,
            //'identifier_fullness' => $request['identifier_fullness'] ?? $nomenclature->kit->identifier_fullness,
            //'identifier_successful' => $request['identifier_successful'] ?? $nomenclature->kit->identifier_successful,
            'unit_id' => $request['unit_id'] ?? $nomenclature->kit->unit_id,
            //'manufacturer_id' => $request['manufacturer_id'] ?? $nomenclature->kit->manufacturer_id,
            'supplier_id' => $request['supplier_id'] ?? $nomenclature->kit->supplier_id,
            'country_id' => $request['country_id'] ?? $nomenclature->kit->country_id,
            'weight_id' => $request['weight_id'] ?? $nomenclature->kit->weight_id,
            //'volume_id' => $request['volume_id'] ?? $nomenclature->kit->volume_id,
        ]);

        if (isset($request['characteristic_value'])) {
            $nomenclature->characteristicValue()->detach();
            foreach ($request['characteristic_value'] as $key => $characteristic) {
                $nomenclature->characteristicValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic_color_value'])) {
            $nomenclature->characteristicColorValue()->detach();
            foreach ($request['characteristic_color_value'] as $key => $characteristic) {
                $nomenclature->characteristicColorValue()->attach($characteristic['id']);
            }
        }

        if (isset($request['characteristic'])) {
            foreach ($request['characteristic'] as $key => $characteristic) {
                $nomenclature->characteristic()->attach($characteristic['id']);
            }
        }

        if (isset($request['property_value'])) {
            $nomenclature->propertyValue()->detach();
            foreach ($request['property_value'] as $key => $property) {
                $nomenclature->propertyValue()->attach($property['id']);
            }
        }

        if (isset($request['categories'])) {
            $nomenclature->categories()->detach();
            foreach ($request['categories'] as $key => $category) {
                $nomenclature->categories()->attach($category['id']);
            }
        }

        if (isset($request['prices'])) {
            foreach ($request['prices'] as $key => $prices) {
                if (!isset($prices['type_price_id'])) {
                    $this->createNomenclaturesPrice($nomenclature->id, $prices['id'], $request['unit_id'] ?? $nomenclature->product->unit_id, $prices['value'], $request['supplier_id'] ?? $nomenclature->product->supplier_id);
                }
//                $nomenclature->prices()->attach($prices['id'], [
//                    'value' => $prices['value'],
//                    'unit_id' => $request['unit_id'] ?? $nomenclature->product->unit_id,
//                    'supplier_id' => $request['supplier_id'] ?? $nomenclature->product->supplier_id,
//                    'date' => $nomenclature->created_at
//                ]);
            }
        }

//        if (isset($request['related'])) {
//            foreach ($request['related'] as $key => $value) {
//                $nomenclature_related[$key]['related_id'] = $value['id'];
//            }
//
//            $nomenclature->related()->sync($nomenclature_related);
//        }
        if (isset($request['kits'])) {
            foreach ($request['kits'] as $key => $value) {
                $nomenclature_kit[$key]['kit_id'] = $value['id'];
                $nomenclature_kit[$key]['count'] = $value['count'];
            }
            $nomenclature->kits()->sync($nomenclature_kit);
        }

        if (isset($request['files'])) {
            foreach ($request['files'] as $key => $value) {
                $files[$key]['file_id'] = $value['id'];
            }
            $nomenclature->files()->sync($files);
        }

        return $this->getNomenclatureKitTable($id);
    }

    public function getWarehouseProducts($id)
    {
        $products = $this->model()
//            ->whereHas('warehouses', function ($q) use ($id) {
//                $q->where('warehouse_id', $id);
//            })
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
//            ->whereNull('group_id')
            ->where(function ($query) use ($id) {
                //product
                $query->where(function ($qk) use ($id) {
                    $qk->where('is_actual', Yesno::YES)
                        ->whereNull('group_id')
                        ->whereHas('warehouses', function ($q) use ($id) {
                            $q->where('warehouse_id', $id)->where('balance', '>', 0);
                        });
                    //groups
                })->orWhere(function ($qp) {
                    $qp->whereHas('groups', function ($q) {
                        $q->where('archive', Yesno::NO)
                            ->where('is_actual', Yesno::YES)
                            ->whereHas('warehouses', function ($q) {
                                $q->where('balance', '>', 0);
                            });
                    });
                });
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'prices',
                'warehouses' => function ($q) use ($id) {
                    $q->where('warehouse_id', $id);
                },
                'groups' => function ($q) use ($id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('product')
                        ->whereHas('warehouses', function ($q) use ($id) {
                            $q->where('balance', '>', 0)->where('warehouse_id', $id);
                        })
                        ->with([
                            'prices',
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            }
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'headers' => TitleJson::get(WarehousesProductsHeader::get(), $this->lang),
            'body' => $this->getProductsTable($products, $this->getPricesHeader()),
            //'body' => $products,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getWarehouseKits($id)
    {
        $kits = $this->model()
            ->whereHas('warehouses', function ($q) use ($id) {
                $q->where('warehouse_id', $id)->where('balance', '>', 0);
            })
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('kit')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'prices',
                'warehouses' => function ($q) use ($id) {
                    $q->where('warehouse_id', $id);
                },
                'kits' => function ($q) use ($id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q) use ($id) {
                                $q->where('archive', Yesno::NO)->where('warehouse_id', $id)->where('balance', '>', 0);
                            },
                            'prices'
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $data = [
            'headers' => TitleJson::get(WarehousesKitsHeader::get(), $this->lang),
            'body' => $this->getKitsTable($kits, $this->getPricesHeader()),
            //'body' => $products,
            'total_page' => $kits->lastPage(),
            'total' => $kits->total()
        ];
    }

    public function getWarehouseProductsAll()
    {
        $products = $this->model()
//            ->whereHas('warehouses')
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
//            ->whereNull('group_id')
            ->where(function ($query) {
                //product
                $query->where(function ($qk) {
                    $qk->where('is_actual', Yesno::YES)
                        ->whereNull('group_id')
                        ->whereHas('warehouses', function ($q) {
                            $q->where('balance', '>', 0);
                        });
                    //groups
                })->orWhere(function ($qp) {
                    $qp->whereHas('groups', function ($q) {
                        $q->where('archive', Yesno::NO)
                            ->where('is_actual', Yesno::YES)
                            ->whereHas('warehouses', function ($q) {
                                $q->where('balance', '>', 0);
                            });
                    });
                });
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'prices',
                'warehouses',
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('warehouses', function ($q) {
                            $q->where('balance', '>', 0);
                        })->with([
                            'product',
                            'prices',
                            'warehouses',
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        //dd($products);
        return $data = [
            'headers' => TitleJson::get(WarehousesProductsHeader::get(), $this->lang),
            'body' => $this->getProductsTable($products, $this->getPricesHeader()),
            //'body' => $products,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getWarehouseKitsAll()
    {
        $kits = $this->model()
            ->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('warehouses', function ($q) {
                $q->where('balance', '>', 0);
            })
            ->whereHas('kit')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'kit' => function ($q) {
                    $q->with(['units', 'suppliers']);
                },
                'prices',
                'kits' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->with([
                            'product',
                            'warehouses' => function ($q)  {
                                $q->where('archive', Yesno::NO)->where('balance', '>', 0);
                            },
                            'prices'
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        //dd($products);
        return $data = [
            'headers' => TitleJson::get(WarehousesKitsHeader::get(), $this->lang),
            'body' => $this->getKitsTable($kits, $this->getPricesHeader()),
            //'body' => $products,
            'total_page' => $kits->lastPage(),
            'total' => $kits->total()
        ];
    }

    public function fillProductsStocks($id)
    {
        $data = $this->request->all();

        $products = $this->model()->whereHas('warehouses', function ($q) use ($id) {
            $q->where('warehouse_id', $id);
        })->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'prices' => function ($q) use ($data) {
                    if (isset($data['price_id']) && isset($data['date'])) {
                        $q->wherePivot('price_id', $data['price_id'] ?? null)->wherePivot('status', Yesno::YES)->wherePivot('date', '<=', $data['date'] ?? null);
                    }
                },
                'warehouses' => function ($q) use ($id) {
                    $q->where('warehouse_id', $id);
                }
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);


        $nomenclatures = $this->getFillProductsStocks($products);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }

            return $data = [
                'sum_price' => $nomenclatures['sum_price'],
                'sum_balance' => $nomenclatures['sum_balance'],
                'nomenclatures' => [
                    'headers' => TitleJson::get(DocumentNomenclatureHeader::get(), $this->lang),
                    'body' => $nomenclature_array,
                ],
                'total_page' => $products->lastPage(),
                'total' => $products->total()
            ];
        } else {
            return $data = [
                'sum_price' => $nomenclatures['sum_price'],
                'sum_balance' => $nomenclatures['sum_balance'],
                'nomenclatures' => [
                    'headers' => TitleJson::get(DocumentNomenclatureHeader::get(), $this->lang),
                    'body' => '',
                ],
                'total_page' => $products->lastPage(),
                'total' => $products->total()
            ];

        }
    }

    public function getProductsWithWarehouse($id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            //->whereHas('warehouses')
            ->whereNull('group_id')
            ->with([
                'product' => function ($q) {
                    $q->with(['units', 'countries']);
                },
                'prices',
                'files',
                'groups' => function ($q) use ($id) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('product')
                        ->with([
                            'product',
                            'files',
                            'prices',
                            'warehouses' => function ($q) use ($id) {
                                $q->where('warehouse_id', $id);
                            },
                        ]);
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        return $products;
    }

    public function getProductWithWarehouse($id)
    {
        $product = $this->model()
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with('units');
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])->find($id);

        return $this->getProductWarehouses($product);
    }

    public function createNomenclatureStocks($products, $warehouse_id)
    {
        if (isset($products)) {
            foreach ($products as $product) {

                $nomenclature = $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id) {
                    $q->where('warehouse_id', $warehouse_id);
                }])->first();

                //return  $nomenclature;
                if (count($nomenclature->warehouses) > 0) {
                    $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id, $product) {
                        $q->where('warehouse_id', $warehouse_id)->increment('balance', $product['balance_base']);
                    }])->first();

                } else {
                    $this->model()->warehouses()->where('id', $product['id'])->attach($warehouse_id, [
                        'nomenclature_id' => $product['id'],
                        'balance' => $product['balance_base']
                    ]);
                    //$nomenclature->warehouses()->increment('balance' , $product['balance']);
                }
            }
        }
    }

    public function writeOffNomenclatureStocks($products, $warehouse_id)
    {
        if (isset($products)) {
            foreach ($products as $product) {
                //dd($product);
                $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id, $product) {
                    $q->where('warehouse_id', $warehouse_id)->decrement('balance', $product['balance_base']);
                }])->first();
//            $nomenclature =  $this->model()->where('id', $product['id'] )->whereHas('warehouses', function ($q) use ($warehouse_id) {
//                $q->where('warehouse_id', $warehouse_id);
//            })->with('warehouses')->first();
//
//            $nomenclature->warehouses()->decrement('balance' , $product->pivot->balance);
            }
        }
    }

    public function incrementReserveNomenclatureStocks($products, $warehouse_id)
    {
        if (isset($products)) {
            foreach ($products as $product) {
                $nomenclature = $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id) {
                    $q->where('warehouse_id', $warehouse_id);
                }])->first();
                if (count($nomenclature->warehouses) > 0) {
                    $this->model()->where('id', $product['id'])->with([
                        'warehouses' => function ($q) use ($warehouse_id, $product) {
                            $q->where('warehouse_id', $warehouse_id)
                                ->increment('reserve', $product['balance_base']);
                        }])->first();
                } else {
                    $this->model()->warehouses()->where('id', $product['id'])->attach($warehouse_id, [
                        'nomenclature_id' => $product['id'],
                        'reserve' => $product['balance_base']
                    ]);
                }
            }
        }
    }

    public function decrementReserveNomenclatureStocks($products, $warehouse_id)
    {
        if (isset($products)) {
            foreach ($products as $product) {
                $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id, $product) {
                    $q->where('warehouse_id', $warehouse_id)->decrement('reserve', $product['balance_base']);
                }])->first();
            }
        }
    }

    public function canceledCapitalizedStocks($products, $warehouse_id)
    {
        foreach ($products as $product) {
            $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id, $product) {
                $q->where('warehouse_id', $warehouse_id)->decrement('balance', $product->pivot->balance);
            }])->first();
//            $nomenclature =  $this->model()->where('id', $product['id'] )->whereHas('warehouses', function ($q) use ($warehouse_id) {
//                $q->where('warehouse_id', $warehouse_id);
//            })->with('warehouses')->first();
//
//            $nomenclature->warehouses()->decrement('balance' , $product->pivot->balance);
        }
    }

    public function canceledCapitalizedWriteOffStocks($products, $warehouse_id)
    {
        foreach ($products as $product) {
            //dd($product);
            $this->model()->where('id', $product['id'])->with(['warehouses' => function ($q) use ($warehouse_id, $product) {
                $q->where('warehouse_id', $warehouse_id)->increment('balance', $product->pivot->balance);
            }])->first();
//            $nomenclature =  $this->model()->where('id', $product['id'] )->whereHas('warehouses', function ($q) use ($warehouse_id) {
//                $q->where('warehouse_id', $warehouse_id);
//            })->with('warehouses')->first();
//
//            $nomenclature->warehouses()->decrement('balance' , $product->pivot->balance);
        }
    }

    public function searchProductsDocument($title)
    {
        return Product::select('nomenclature_id', 'short_title', 'convert_id', 'sku')
            ->where(function ($q) use ($title) {
                $q->whereHas('nomenclatures', function ($q) {
                    $q->where('archive', Yesno::NO);
                })->where('short_title', 'like', '%' . $title . '%');
            })
            ->orWhere(function ($q) use ($title) {
                $q->whereHas('nomenclatures', function ($q) {
                    $q->where('archive', Yesno::NO);
                })->where('convert_id', 'like', '%' . $title . '%');
            })
            ->orWhere(function ($q) use ($title) {
                $q->whereHas('nomenclatures', function ($q) {
                    $q->where('archive', Yesno::NO);
                })->where('sku', 'like', '%' . $title . '%');
            })
            ->paginate($this->request->paginate);
    }

    public function searchProductsDocumentTable($title)
    {
        $products = $this->model()->whereHas('category', function ($q) {
            $q->where('archive', Yesno::NO);
        })
            ->whereNull('group_id')
            ->whereHas('product', function ($q) use ($title) {
                $q->where('short_title', 'like', '%' . $title . '%');
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('product')
                        ->with([

                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            }
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])
            ->orderBy('order', 'ASC')
            ->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function searchProductsDocumentInCategoryTable($title, $category_id)
    {
        $products = $this->model()->whereHas('category', function ($q) {
            $q->where('archive', Yesno::NO);
        })
            ->whereIn('category_id', $category_id)
            ->whereNull('group_id')
            ->whereHas('product', function ($q) use ($title) {
                $q->where('short_title', 'like', '%' . $title . '%');
            })
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'groups' => function ($q) {
                    $q->where('archive', Yesno::NO)
                        ->where('is_actual', Yesno::YES)
                        ->whereHas('product')
                        ->with([
                            'product',
                            'warehouses' => function ($q) {
                                $q->where('archive', Yesno::NO);
                            }
                        ]);
                },
                'warehouses' => function ($q) {
                    $q->where('archive', Yesno::NO);
                },
            ])
            ->orderBy('order', 'ASC')
            ->paginate($this->request->paginate);

        return $data = [
            'body' => $this->getSelectProductsTable($products),
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
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

    public function getSelectRelatedProductsHeader()
    {
        return TitleJson::get(SelectRelatedProductsHeader::get(), $this->lang);
    }

    public function tableWriteOfStocksProduct($id)
    {
        $data = $this->request->all();

        $nomenclature = $this->model()->with([
            'warehouses' => function ($q) use ($data) {
                $q->wherePivot('warehouse_id', $data['warehouse_id']);
            },
            'product' => function ($q) {
                $q->with(['units']);
            },
        ])->find($id);

        $data = [
            'headers' => $this->getDocumentNomenclatureStockHeader(),
            'body' => $this->getProductDocumentsStockTable($nomenclature),
        ];

        return $data;
    }

    public function tableStocksProduct($id)
    {
        $data = $this->request->all();

        $nomenclature = $this->model()->with([
            'prices' => function ($q) use ($data) {
                $q->wherePivot('price_id', $data['price_id'])->wherePivot('status', Yesno::YES)->wherePivot('date', '<=', $data['date']);
            },
            'product' => function ($q) {
                $q->with(['units']);
            },
        ])->find($id);

        $data = [
            'headers' => $this->getDocumentNomenclatureHeader(),
            'body' => $this->getProductDocumentsTable($nomenclature, $data['price_id']),
        ];

        return $data;
    }

    public function getNomenclaturesData()
    {
        return $this->model()->whereIn('id', $this->request->data);
    }

    public function iterateOverArray($array)
    {
        foreach ($array as $nomenclature) {
            $nomenclature_array[] = $nomenclature;
        }

        return $nomenclature_array;
    }

    public function getSelectedCharacteristicsKits()
    {
        $data = $this->getNomenclaturesData();
        $products = $data->with([
            'characteristicValue' => function ($cv) {
                $cv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristicColorValue' => function ($ccv) {
                $ccv->whereHas('characteristic', function ($qp) {
                    $qp->where('archive', Yesno::NO)->orderBy('order', 'ASC');
                })->with('characteristic');
            },
            'characteristic' => function ($ccv) {
                $ccv->with(['characteristicValue', 'characteristicColorValue']);
            },
        ])->get();

        $data = $this->getCharacteristicsKits($products);

        return $data;
    }

    public function getSelectedKits()
    {
        $data = $this->getNomenclaturesData();
        $products = $data->with([
            'prices' => function ($q) {
                $q->wherePivot('price_id', 1)->wherePivot('status', Yesno::YES);
            },
            'product' => function ($q) {
                $q->with(['units']);
            },
            'warehouses' => function ($q) {
                $q->where('archive', Yesno::NO);
            },
        ])->get();

        $nomenclatures = $this->getSelectedKitsTable($products);

        if (isset($nomenclatures['arr'])) {
            $nomenclature_array = $this->iterateOverArray($nomenclatures['arr']);
        } else {
            $nomenclature_array = [];
        }

        return $data = [
            'headers' => TitleJson::get(KitsTableHeader::get(), $this->lang),
            'body' => $nomenclature_array,
            'sum_price' => $nomenclatures['sum_price']
        ];
    }

    public function selectedNomenclatures($nomenclatures)
    {
        $data = $this->request->all();

        $products = $this->model()->whereIn('id', $nomenclatures)->with([
            'prices' => function ($q) use ($data) {
                if (isset($data['price_id']) && isset($data['date'])) {
                    $q->wherePivot('price_id', $data['price_id'])->wherePivot('status', Yesno::YES)->wherePivot('date', '<=', $data['date']);
                }
            },
            'product' => function ($q) {
                $q->with(['units']);
            },
            'kit' => function ($q) {
                $q->with(['units']);
            },
            'kits' => function ($q) use ($data) {
                $q->where('archive', Yesno::NO)
                    ->where('is_actual', Yesno::YES)
                    ->with([
                        'product',
                        'warehouses' => function ($q) {
                            $q->where('archive', Yesno::NO);
                        },
                        'prices' => function ($q) use ($data) {
                            if (isset($data['price_id']) && isset($data['date'])) {
                                $q->wherePivot('price_id', $data['price_id'])->wherePivot('status', Yesno::YES)->wherePivot('date', '<=', $data['date']);
                            }
                        },
                    ]);
            },
            'warehouses' => function ($q) use ($data) {
                if (isset($data['warehouse_id'])) {
                    $q->where('warehouse_id', $data['warehouse_id']);
                }
            }
        ])->get();
        $nomenclatures = $this->getSelectedNomenclaturesDocumentsTable($products);
        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }
        return $nomenclature_array;
    }

    public function selectedServices($nomenclatures)
    {
        $data = $this->request->all();

        $products = $this->model()->whereIn('id', $nomenclatures)->with([
            'prices' => function ($q) use ($data) {
                if (isset($data['price_id']) && isset($data['date'])) {
                    $q->wherePivot('price_id', $data['price_id'])->wherePivot('status', Yesno::YES)->wherePivot('date', '<=', $data['date']);
                }
            },
            'service' => function ($q) {
                $q->with(['units']);
            }
//            },
//            'warehouses' => function ($q) use ($data) {
//                if (isset($data['warehouse_id'])) {
//                    $q->where('warehouse_id', $data['warehouse_id']);
//                }
//            }
        ])->get();

        $nomenclatures = $this->getSelectedServicesDocumentsTable($products);
        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }
        return $nomenclature_array;
    }

    //related
    public function indexRelatedProducts($id)
    {
        return $this->model()
            ->where('id', $id)
            ->where('is_actual', Yesno::YES)
            ->where('archive', Yesno::NO)
            ->whereHas('product')
            //->whereHas('related')
            ->with([
                'product' => function ($q) {
                    $q->with('units');
                },
                'related' => function ($q) {
                    $q->with([
                        'prices' => function ($q) {
                            $q->wherePivot('status', Yesno::YES);
                        }
                    ]);
                },
                'analog' => function ($q) {
                    $q->with([
                        'prices' => function ($q) {
                            $q->wherePivot('status', Yesno::YES);
                        }
                    ]);
                },
                'prices' => function ($q) {
                    $q->wherePivot('status', Yesno::YES);
                },
                'warehouses',
                'files'
            ])->orderBy('order', 'ASC')->first();
    }

    public function getRelatedProducts($id)
    {
        $products = $this->indexRelatedProducts($id);
        $nomenclatures = $this->getRelatedProductsTable($products->related);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $nomenclature_array;
    }

    public function getAnalogProducts($id)
    {
        $products = $this->indexRelatedProducts($id);
        $nomenclatures = $this->getRelatedProductsTable($products->analog);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $nomenclature_array;
    }


    public function getTableAnalogProducts($id)
    {
        $products = $this->indexRelatedProducts($id);
        if (isset($products->analog) && count($products->analog) > 0) {
            $products_analog = PaginateHelper::paginate($products->analog, $this->request->paginate);
            $nomenclatures = $this->tableRelatedNomenclaturesInNomenclature($products_analog, $this->getPricesHeader());
            $item_ids = $this->getItemIds($products->analog);

            if (isset($nomenclatures['arr']) && isset($item_ids['arr'])) {
                foreach ($nomenclatures['arr'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
                foreach ($item_ids['arr'] as $value) {
                    $item_ids_array[] = $value;
                }
            }

            return $data = [
                'item_ids' => $item_ids_array,
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => $nomenclature_array,
                'total_page' => $products_analog->lastPage(),
                'total' => $products_analog->total()
            ];
        } else {
            return $data = [
                'item_ids' => [],
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => [],
                'total_page' => 1,
                'total' => 0
            ];
        }
    }

    public function getTableRelatedProducts($id)
    {
        $products = $this->indexRelatedProducts($id);

        if (isset($products->related) && count($products->related) > 0) {
            $products_related = PaginateHelper::paginate($products->related, $this->request->paginate);
            $nomenclatures = $this->tableRelatedNomenclaturesInNomenclature($products_related, $this->getPricesHeader());
            $item_ids = $this->getItemIds($products->related);

            if (isset($nomenclatures['arr']) && isset($item_ids['arr'])) {
                foreach ($nomenclatures['arr'] as $nomenclature) {
                    $nomenclature_array[] = $nomenclature;
                }
                foreach ($item_ids['arr'] as $value) {
                    $item_ids_array[] = $value;
                }
            }

            return $data = [
                'item_ids' => $item_ids_array,
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => $nomenclature_array,
                'total_page' => $products_related->lastPage(),
                'total' => $products_related->total()
            ];
        } else {
            return $data = [
                'item_ids' => [],
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => [],
                'total_page' => 1,
                'total' => 0
            ];
        }

    }

    public function getProductsInRelatedOrAnalogAll()
    {
        $products = $this->model()
            ->where('is_actual', Yesno::YES)
            ->where('archive', Yesno::NO)
            ->whereHas('product')
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->with([
                'product' => function ($q) {
                    $q->with('units');
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getProductsInRelatedOrAnalogTable($products);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $data = [
            'body' => $nomenclature_array,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function getProductsInRelatedOrAnalog($id)
    {
        $products = $this->model()
            ->where('is_actual', Yesno::YES)
            ->where('archive', Yesno::NO)
            ->whereIn('category_id', $id)
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with('units');
                },
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getProductsInRelatedOrAnalogTable($products);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $data = [
            'body' => $nomenclature_array,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function createRelatedProducts($id)
    {
        $product = $this->findNomenclature($id);

        if (isset($this->request->data) && count($this->request->data) > 0) {
            foreach ($this->request->data as $key => $value) {
                $data[$key]['related_id'] = $value['id'];
            }
            $product->related()->attach($data);
        }
    }

    public function deleteRelatedProducts($id)
    {
        $product = $this->findNomenclature($id);

        if (isset($this->request->data) && count($this->request->data) > 0) {
            foreach ($this->request->data as $key => $value) {
                $data[$key]['related_id'] = $value['id'];
            }
            $product->related()->detach($data);
        } else {
            $product->related()->detach();
        }
    }

    public function createAnalogProducts($id)
    {
        $product = $this->findNomenclature($id);

        if (isset($this->request->data) && count($this->request->data) > 0) {
            foreach ($this->request->data as $key => $value) {
                $data[$key]['analog_id'] = $value['id'];
            }
            $product->analog()->attach($data);
        }
    }

    public function deleteAnalogProducts($id)
    {
        $product = $this->findNomenclature($id);

        if (isset($this->request->data) && count($this->request->data) > 0) {
            foreach ($this->request->data as $key => $value) {
                $data[$key]['analog_id'] = $value['id'];
            }
            $product->analog()->detach($data);
        } else {
            $product->analog()->detach();
        }
    }

//    public function updateOrCreateRelatedProducts($id)
//    {
//        $product = $this->model()->where('id', $id)->first();
//
//        if (isset($this->request->data) && count($this->request->data) > 0) {
//            foreach ($this->request->data as $key => $value) {
//                $data[$key]['related_id'] = $value['id'];
//            }
//            $product->related()->sync($data);
//        } else {
//            $product->related()->detach();
//        }
//    }

    public function updateOrCreateAnalogProducts($id)
    {
        $product = $this->model()->where('id', $id)->first();

        if (isset($this->request->data) && count($this->request->data) > 0) {
            foreach ($this->request->data as $key => $value) {
                $data[$key]['analog_id'] = $value['id'];
            }
            $product->analog()->sync($data);
        } else {
            $product->analog()->detach();
        }
    }

    public function getTableRelatedProduct($id)
    {
        $product = $this->indexRelatedProducts($id);
        $nomenclature = $this->tableRelatedProduct($product, $this->getPricesHeader());

        if (isset($nomenclature)) {
            return $data = [
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => $nomenclature,
            ];
        } else {
            return $data = [
                'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
                'body' => ''
            ];
        }
    }

    public function getSelectRelatedProducts($id)
    {
        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereIn('category_id', $id)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'prices'
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getRelatedProductsTable($products);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                unset($nomenclature['cells']['balance']);
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $data = [
            'body' => $nomenclature_array,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

//    public function archive($query)
//    {
//        $query->where('archive', Yesno::NO)
//            ->where('is_actual', Yesno::YES);
//    }

    public function getSelectRelatedProductsAll()
    {
//        $products = $this->model();
//        $this->archive($products);
//
//        $products->whereHas('category', function ($q) {
//            $q->where('archive', Yesno::NO);
//        })
//            ->whereHas('product')
//            ->with([
//                'product' => function ($q) {
//                    $q->with(['units']);
//                },
//                'prices'
//            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);


        $products = $this->model()->where('archive', Yesno::NO)
            ->where('is_actual', Yesno::YES)
            ->whereHas('category', function ($q) {
                $q->where('archive', Yesno::NO);
            })
            ->whereHas('product')
            ->with([
                'product' => function ($q) {
                    $q->with(['units']);
                },
                'prices'
            ])->orderBy('order', 'ASC')->paginate($this->request->paginate);

        $nomenclatures = $this->getRelatedProductsTable($products);

        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                //unset($nomenclature['cells']['balance']);
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }

        return $data = [
            'body' => $nomenclature_array,
            'total_page' => $products->lastPage(),
            'total' => $products->total()
        ];
    }

    public function selectedRelatedOrAnalogNomenclatures($nomenclatures)
    {
        $products = $this->model()->whereIn('id', $nomenclatures)->get();
        $nomenclatures = $this->tableRelatedOrAnalogProducts($products, $this->getPricesHeader());
        if (isset($nomenclatures['arr'])) {
            foreach ($nomenclatures['arr'] as $nomenclature) {
                $nomenclature_array[] = $nomenclature;
            }
        } else {
            $nomenclature_array = [];
        }
        return $data = [
            'headers' => TitleJson::get(TableRelatedProductsHeader::get(), $this->lang),
            'body' => $nomenclature_array,
        ];
    }


    public function selectedRelatedNomenclaturesInNomenclature($nomenclatures)
    {
        $products = $this->model()->whereIn('id', $nomenclatures)->get();
        $nomenclatures = $this->tableRelatedNomenclaturesInNomenclature($products, $this->getPricesHeader());

        foreach ($nomenclatures['arr'] as $nomenclature) {
            $nomenclature_array[] = $nomenclature;
        }
        return $data = [
            'headers' => TitleJson::get(tableRelatedNomenclaturesInNomenclatureHeader::get(), $this->lang),
            'body' => $nomenclature_array,
        ];
    }

    //prices

//    public function getNomenclaturePrices()
//    {
//        return $this->model()->where('archive', Yesno::NO)
//            ->where('is_actual', Yesno::YES)
//            ->with()
//    }
}
