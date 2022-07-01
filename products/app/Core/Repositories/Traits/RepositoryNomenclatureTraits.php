<?php

namespace App\Core\Repositories\Traits;

use App\Core\Helpers\TitleJson;
use App\Core\Model\Yesno;
use App\Documents\Models\Document;
use App\Nomenclatures\Models\KitsTableHeader;
use App\Nomenclatures\Models\Nomenclature;
use App\Nomenclatures\Models\TableRelatedNomenclaturesInNomenclatureHeader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Directories\Models\Currencies\DirectoryCurrency;


trait RepositoryNomenclatureTraits
{
    public function getDefaultCurrency()
    {
        return DirectoryCurrency::where('is_default', Yesno::YES)->first();
    }


    public function getProductTable($item, $prices)
    {

        $data = [];

        $data['id'] = $item->id;
        //$data[$key]['cells']['id'] = $item->id;
        $data['cells']['convert_id'] = $item->product->convert_id;
        $data['cells']['short_title'] = $item->product->short_title;
        $data['cells']['is_visible'] = $item->is_visible;
        if (isset($item->product->dock_title)) {
            $data['cells']['dock_title'] = $item->product->dock_title;
        } else {
            $data['cells']['dock_title'] = null;
        }
        //$data[$key]['cells']['desc'] = $value->desc;
        $data['cells']['sku'] = $item->product->sku;
        $data['cells']['lower_limit'] = $item->product->lower_limit;
        //$data[$key]['cells']['general_count'] = $value->general_count;

        $data['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
        $data['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
        $data['cells']['min_price'] = $item->product->min_price ?? null;
        $data['cells']['min_price_option'] = [
            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
        ];
        $data['cells']['packing'] = $item->product->packing;
        $data['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
        if (isset($item->product->volume)) {
            $data['cells']['volumes'] = $item->product->volume;
        } else {
            $data['cells']['volumes'] = null;
        }
        if (isset($item->product->weight) && isset($item->product->weights->title)) {
            $data['cells']['weights'] = $item->product->weight . ' ' . TitleJson::getArray($item->product->weights->title, $this->lang);
        } else {
            $data['cells']['weights'] = null;
        }

        for ($i = 1; $i <= $prices->count(); $i++) {
            $data['cells']['price_' . $i] = null;

            $data['cells']['price_' . $i . '_option'] = [
                'id' => null,
                'is_default' => null,
                'is_rounding_without' => null,
                'is_rounding_with' => null,
                'currency_title' => null,
            ];
        }

        foreach ($item->prices->unique('id') as $price) {
            $data['cells']['price_' . $price->id] = $price->pivot->value;

            $data['cells']['price_' . $price->id . '_option'] = [
                'id' => $price->pivot->id,
                'is_default' => $price->is_default,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
            ];
        }
        $data['is_groups'] = $item->product->is_groups;

        if (isset($item->groups) && $item->groups->count() > 0) {

            $data['group_general_count'] = 0;
            $data['group_reserve'] = 0;
            foreach ($item->groups as $k => $group) {

                $data['groups'][$k]['id'] = $group->id;
                $data['groups'][$k]['cells']['convert_id'] = $group->product->convert_id;
                $data['groups'][$k]['cells']['short_title'] = $group->product->short_title ?? null;
                $data['groups'][$k]['cells']['is_visible'] = $group->is_visible;
                if (isset($group->product->dock_title)) {
                    $data['groups'][$k]['cells']['dock_title'] = $group->product->dock_title;
                } else {
                    $data['groups'][$k]['cells']['dock_title'] = null;
                }
                //$data[$key]['cells']['desc'] = $value->desc;
                $data['groups'][$k]['cells']['sku'] = $group->product->sku ?? null;
                $data['groups'][$k]['cells']['lower_limit'] = $group->product->lower_limit ?? null;
                //$data[$key]['cells']['general_count'] = $value->general_count;
                $data['group_general_count'] = $data['group_general_count'] + $group->warehouses->sum('pivot.balance') ?? null;
                $data['group_reserve'] = $data['group_reserve'] + $group->warehouses->sum('pivot.reserve') ?? null;

                $data['groups'][$k]['cells']['balance'] = $group->warehouses->sum('pivot.balance') - $group->warehouses->sum('pivot.reserve') . '/' . $group->warehouses->sum('pivot.balance');
                $data['groups'][$k]['cells']['reserve'] = $group->warehouses->sum('pivot.reserve') ?? null;
                $data['groups'][$k]['cells']['min_price'] = $group->product->min_price ?? null;

                $data['groups'][$k]['cells']['min_price_option'] = [
                    'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                ];
                $data['groups'][$k]['cells']['packing'] = $group->product->packing ?? null;
                $data['groups'][$k]['cells']['units'] = $group->product->units->title ? TitleJson::getArray($group->product->units->title, $this->lang) : null;
                if (isset($group->product->volume)) {
                    $data['groups'][$k]['cells']['volumes'] = $group->product->volume;
                } else {
                    $data['groups'][$k]['cells']['volumes'] = null;
                }
                if (isset($group->product->weight) && isset($group->product->weights->title)) {
                    $data['groups'][$k]['cells']['weights'] = $group->product->weight . ' ' . TitleJson::getArray($group->product->weights->title, $this->lang);
                } else {
                    $data['groups'][$k]['cells']['weights'] = null;
                }
                //$data[$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                for ($i = 1; $i <= $prices->count(); $i++) {
                    $data['groups'][$k]['cells']['price_' . $i] = null;
                    $data['groups'][$k]['cells']['price_' . $i . '_option'] = [
                        'id' => null,
                        'is_default' => null,
                        'is_rounding_without' => null,
                        'is_rounding_with' => null,
                        'currency_title' => null,

                    ];
                }
                foreach ($group->prices->unique('id') as $price) {
                    $data['groups'][$k]['cells']['price_' . $price->id] = $price->pivot->value;
                    $data['groups'][$k]['cells']['price_' . $price->id . '_option'] = [
                        'id' => $price->pivot->id,
                        'is_default' => $price->is_default,
                        'is_rounding_without' => $price->is_rounding_without,
                        'is_rounding_with' => $price->is_rounding_with,
                        'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                    ];
                }

            }
            $data['balance'] = $data['group_general_count'] - $data['group_reserve'] . '/' . $data['group_general_count'];
            $data['cells']['balance'] = $data['group_general_count'] - $data['group_reserve'] . '/' . $data['group_general_count'];

        }

        return $data;
    }

    public function getKitTable($item, $prices)
    {
        $data = [];
        $data['id'] = $item->id;
        //$data[$key]['cells']['id'] = $item->id;
        $data['cells']['convert_id'] = $item->kit->convert_id;
        $data['cells']['short_title'] = $item->kit->short_title;
        $data['cells']['is_visible'] = $item->is_visible;
        if (isset($item->kit->dock_title)) {
            $data['cells']['dock_title'] = $item->kit->dock_title;
        } else {
            $data['cells']['dock_title'] = null;
        }
        //$data[$key]['cells']['desc'] = $value->desc;
        $data['cells']['sku'] = $item->kit->sku;
        $data['cells']['lower_limit'] = $item->kit->lower_limit;
        //$data[$key]['cells']['general_count'] = $value->general_count;

        $data['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
        $data['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
        $data['cells']['min_price'] = $item->kit->min_price ?? null;
        $data['cells']['min_price_option'] = [
            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
        ];
        $data['cells']['packing'] = $item->kit->packing;
        $data['cells']['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
        if (isset($item->kit->volume)) {
            $data['cells']['volumes'] = $item->kit->volume;
        } else {
            $data['cells']['volumes'] = null;
        }
        if (isset($item->kit->weight) && isset($item->kit->weights->title)) {
            $data['cells']['weights'] = $item->kit->weight . ' ' . TitleJson::getArray($item->kit->weights->title, $this->lang);
        } else {
            $data['cells']['weights'] = null;
        }

        for ($i = 1; $i <= $prices->count(); $i++) {
            $data['cells']['price_' . $i] = null;

            $data['cells']['price_' . $i . '_option'] = [
                'id' => null,
                'is_default' => null,
                'is_rounding_without' => null,
                'is_rounding_with' => null,
                'currency_title' => null,
            ];
        }

        foreach ($item->prices->unique('id') as $price) {
            $data['cells']['price_' . $price->id] = $price->pivot->value;

            $data['cells']['price_' . $price->id . '_option'] = [
                'id' => $price->pivot->id,
                'is_default' => $price->is_default,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
            ];
        }

        if (isset($item->kits) && $item->kits->count() > 0) {

            $data['kit_general_count'] = 0;
            $data['kit_reserve'] = 0;
            foreach ($item->kits as $k => $val) {

                $data['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;
                $data['kits'][$k]['id'] = $val->id;
                $data['kits'][$k]['cells']['convert_id'] = $val->product->convert_id;
                $data['kits'][$k]['cells']['short_title'] = $val->product->short_title ?? null;
                $data['kits'][$k]['cells']['is_visible'] = $val->is_visible;
                if (isset($val->product->dock_title)) {
                    $data['kits'][$k]['cells']['dock_title'] = $val->product->dock_title;
                } else {
                    $data['kits'][$k]['cells']['dock_title'] = null;
                }
                //$data[$key]['cells']['desc'] = $value->desc;
                $data['kits'][$k]['cells']['sku'] = $val->product->sku ?? null;
                $data['kits'][$k]['cells']['lower_limit'] = $val->product->lower_limit ?? null;
                //$data[$key]['cells']['general_count'] = $value->general_count;
                $data['kit_general_count'] = $data['kit_general_count'] + $val->warehouses->sum('pivot.balance') ?? null;
                $data['kit_reserve'] = $data['kit_reserve'] + $val->warehouses->sum('pivot.reserve') ?? null;

                $data['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve') . '/' . $val->warehouses->sum('pivot.balance');
                $data['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve') ?? null;
                $data['kits'][$k]['cells']['min_price'] = $val->product->min_price ?? null;

                $data['kits'][$k]['cells']['min_price_option'] = [
                    'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                ];
                $data['kits'][$k]['cells']['packing'] = $val->product->packing ?? null;
                if (isset($val->product->units->title)) {
                    $data['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                } else {
                    $data['kits'][$k]['cells']['units'] = null;
                }
                if (isset($val->product->volume)) {
                    $data['kits'][$k]['cells']['volumes'] = $val->product->volume;
                } else {
                    $data['kits'][$k]['cells']['volumes'] = null;
                }
                if (isset($val->product->weight) && isset($val->product->weights->title)) {
                    $data['kits'][$k]['cells']['weights'] = $val->product->weight . ' ' . TitleJson::getArray($val->product->weights->title, $this->lang);
                } else {
                    $data['kits'][$k]['cells']['weights'] = null;
                }
                //$data[$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                for ($i = 1; $i <= $prices->count(); $i++) {
                    $data['kits'][$k]['cells']['price_' . $i] = null;
                    $data['kits'][$k]['cells']['price_' . $i . '_option'] = [
                        'id' => null,
                        'is_default' => null,
                        'is_rounding_without' => null,
                        'is_rounding_with' => null,
                        'currency_title' => null,

                    ];
                }
                foreach ($val->prices->unique('id') as $price) {
                    $data['kits'][$k]['cells']['price_' . $price->id] = $price->pivot->value;
                    $data['kits'][$k]['cells']['price_' . $price->id . '_option'] = [
                        'id' => $price->pivot->id,
                        'is_default' => $price->is_default,
                        'is_rounding_without' => $price->is_rounding_without,
                        'is_rounding_with' => $price->is_rounding_with,
                        'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                    ];
                }

            }
            $data['balance'] = $data['kit_general_count'] - $data['kit_reserve'] . '/' . $data['kit_general_count'];
            $data['cells']['balance'] = $data['kit_general_count'] - $data['kit_reserve'] . '/' . $data['kit_general_count'];
            $data['cells']['possible_balance_kits'] = (int)collect($data['kits'])->min('count_kit');

        }
//        dd($item->kits);
        return $data;
    }


    public function getServiceTable($item, $prices)
    {
        $data = [];

        $data['id'] = $item->id;
        //$data[$key]['cells']['id'] = $item->id;
        $data['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
        $data['cells']['short_title'] = $item->service->short_title;
        $data['cells']['is_visible'] = $item->is_visible;
        if (isset($item->service->dock_title)) {
            $data['cells']['dock_title'] = $item->service->dock_title;
        } else {
            $data['cells']['dock_title'] = null;
        }
        $data['cells']['sku'] = $item->service->sku;
        $data['cells']['min_price'] = $item->service->min_price ?? null;
        $data['cells']['min_price_option'] = [
            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
        ];

        if (isset($item->service->suppliers)) {
            $data['cells']['suppliers'] = $item->service->suppliers->title_supplier;
        } else {
            $data['cells']['suppliers'] = null;
        }

//        if (isset($item->service->organizations)) {
//            $data['cells']['organizations'] = $item->service->organizations->title;
//        } else {
//            $data['cells']['organizations'] = null;
//        }

        $data['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);

        for ($i = 1; $i <= $prices->count(); $i++) {
            $data['cells']['price_' . $i] = null;

            $data['cells']['price_' . $i . '_option'] = [
                'id' => null,
                'is_default' => null,
                'is_rounding_without' => null,
                'is_rounding_with' => null,
                'currency_title' => null,
            ];
        }

        foreach ($item->prices->unique('id') as $price) {
            $data['cells']['price_' . $price->id] = $price->pivot->value;
            $data['cells']['price_' . $price->id . '_option'] = [
                'id' => $price->pivot->id,
                'is_default' => $price->is_default,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
            ];
        }

        return $data;
    }

    public function getNomenclaturesTable($nomenclatures, $prices)
    {
        $data = [];
        foreach ($nomenclatures as $key => $item) {

            $data[$key]['id'] = $item->id;
            $data[$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);

            if (isset($item->product)) {
                $data[$key]['type'] = Nomenclature::PRODUCT;
                $data[$key]['cells']['short_title'] = $item->product->short_title;
                if (isset($item->product->dock_title)) {
                    $data[$key]['cells']['dock_title'] = $item->product->dock_title;
                } else {
                    $data[$key]['cells']['dock_title'] = null;
                }
                $data[$key]['cells']['sku'] = $item->product->sku;
                $data[$key]['cells']['lower_limit'] = $item->product->lower_limit;
                $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
                $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                $data[$key]['cells']['min_price'] = $item->product->min_price ?? null;
                $data[$key]['cells']['min_price_option'] = [
                    'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                ];

                $data[$key]['cells']['packing'] = $item->product->packing;

                if (isset($item->product->units)) {
                    $data[$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                } else {
                    $data[$key]['cells']['units'] = null;
                }
                if (isset($item->product->volume)) {
                    $data[$key]['cells']['volumes'] = $item->product->volume;
                } else {
                    $data[$key]['cells']['volumes'] = null;
                }
                if (isset($item->product->weight) && isset($item->product->weights->title)) {
                    $data[$key]['cells']['weights'] = $item->product->weight . ' ' . TitleJson::getArray($item->product->weights->title, $this->lang);
                } else {
                    $data[$key]['cells']['weights'] = null;
                }
                if (isset($item->product->countries) && isset($item->product->countries->title)) {
                    $data[$key]['cells']['countries'] = TitleJson::getArray($item->product->countries->title, $this->lang);
                } else {
                    $data[$key]['cells']['countries'] = null;
                }
                if (isset($item->product->suppliers)) {
                    $data[$key]['cells']['supplier'] = $item->product->suppliers->title_supplier;
                } else {
                    $data[$key]['cells']['supplier'] = null;
                }

                //$data[$key]['cells']['organizations'] = null;

                $data[$key]['is_groups'] = $item->product->is_groups;
                $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;
                if (isset($item->groups) && $item->groups->count() > 0) {

                    $data[$key]['group_general_count'] = 0;
                    $data[$key]['group_reserve'] = 0;
                    foreach ($item->groups as $k => $group) {

                        $data[$key]['groups'][$k]['id'] = $group->id;
                        $data[$key]['groups'][$k]['cells']['convert_id'] = $group->product->convert_id;
                        if (isset($group->product->short_title)) {
                            $data[$key]['groups'][$k]['cells']['short_title'] = $group->product->short_title;
                        } else {
                            $data[$key]['groups'][$k]['cells']['short_title'] = null;
                        }

                        $data[$key]['groups'][$k]['cells']['is_visible'] = $group->is_visible;
                        if (isset($group->product->dock_title)) {
                            $data[$key]['groups'][$k]['cells']['dock_title'] = $group->product->dock_title;
                        } else {
                            $data[$key]['groups'][$k]['cells']['dock_title'] = null;
                        }
                        if (isset($group->product->sku)) {
                            $data[$key]['groups'][$k]['cells']['sku'] = $group->product->sku;
                        } else {
                            $data[$key]['groups'][$k]['cells']['sku'] = null;
                        }

                        if (isset($group->product->lower_limit)) {
                            $data[$key]['groups'][$k]['cells']['lower_limit'] = $group->product->lower_limit;
                        } else {
                            $data[$key]['groups'][$k]['cells']['lower_limit'] = null;
                        }
                        if (isset($group->warehouses->pivot)) {
                            $data[$key]['group_general_count'] = $data[$key]['group_general_count'] + $group->warehouses->sum('pivot.balance');
                        }
                        if (isset($group->warehouses)) {
                            $data[$key]['group_reserve'] = $data[$key]['group_reserve'] + $group->warehouses->sum('pivot.reserve');
                        }

                        if (isset($group->warehouses)) {
                            $data[$key]['groups'][$k]['cells']['balance'] = $group->warehouses->sum('pivot.balance') - $group->warehouses->sum('pivot.reserve') . '/' . $group->warehouses->sum('pivot.balance');
                        }

                        $data[$key]['groups'][$k]['cells']['reserve'] = $group->warehouses->sum('pivot.reserve') ?? null;
                        $data[$key]['groups'][$k]['cells']['min_price'] = $group->product->min_price ?? null;
                        $data[$key]['groups'][$k]['cells']['min_price_option'] = [
                            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                        ];
                        $data[$key]['groups'][$k]['cells']['packing'] = $group->product->packing ?? null;
                        if (isset($group->product->units->title)) {
                            $data[$key]['groups'][$k]['cells']['units'] = TitleJson::getArray($group->product->units->title, $this->lang);
                        } else {
                            $data[$key]['groups'][$k]['cells']['units'] = null;
                        }
                        if (isset($group->product->volume)) {
                            $data[$key]['groups'][$k]['cells']['volumes'] = $group->product->volume;
                        } else {
                            $data[$key]['groups'][$k]['cells']['volumes'] = null;
                        }
                        if (isset($group->product->weight) && isset($group->product->weights->title)) {
                            $data[$key]['groups'][$k]['cells']['weights'] = $group->product->weight . ' ' . TitleJson::getArray($group->product->weights->title, $this->lang);
                        } else {
                            $data[$key]['groups'][$k]['cells']['weights'] = null;
                        }
                        if (isset($group->product->countries) && isset($group->product->countries->title)) {
                            $data[$key]['groups'][$k]['cells']['countries'] = TitleJson::getArray($group->product->countries->title, $this->lang);
                        } else {
                            $data[$key]['groups'][$k]['cells']['countries'] = null;
                        }
                        if (isset($group->product->suppliers)) {
                            $data[$key]['groups'][$k]['cells']['supplier'] = $group->product->suppliers->title_supplier;
                        } else {
                            $data[$key]['groups'][$k]['cells']['supplier'] = null;
                        }
                        for ($i = 1; $i <= $prices->count(); $i++) {
                            $data[$key]['groups'][$k]['cells']['price_' . $i] = null;
                            $data[$key]['groups'][$k]['cells']['price_' . $i . '_option'] = [
                                'id' => null,
                                'is_default' => null,
                                'is_rounding_without' => null,
                                'is_rounding_with' => null,
                                'currency_title' => null,
                            ];
                        }
                        foreach ($group->prices->unique('id') as $price) {
                            $data[$key]['groups'][$k]['cells']['price_' . $price->id] = $price->pivot->value;
                            $data[$key]['groups'][$k]['cells']['price_' . $price->id . '_option'] = [
                                'id' => $price->pivot->id,
                                'is_default' => $price->is_default,
                                'is_rounding_without' => $price->is_rounding_without,
                                'is_rounding_with' => $price->is_rounding_with,
                                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                            ];
                        }
                        $data[$key]['groups'][$k]['cells']['basket_orders_count'] = $group->basketOrders->count ?? 0;
                    }
                    $data[$key]['balance'] = $data[$key]['group_general_count'] - $data[$key]['group_reserve'] . '/' . $data[$key]['group_general_count'];
                    $data[$key]['cells']['balance'] = $data[$key]['group_general_count'] - $data[$key]['group_reserve'] . '/' . $data[$key]['group_general_count'];

                }
            } elseif (isset($item->service)) {
                $data[$key]['type'] = Nomenclature::SERVICE;
                $data[$key]['cells']['short_title'] = $item->service->short_title;
                if (isset($item->service->dock_title)) {
                    $data[$key]['cells']['dock_title'] = $item->service->dock_title;
                } else {
                    $data[$key]['cells']['dock_title'] = null;
                }
                $data[$key]['cells']['sku'] = $item->service->sku;

                $data[$key]['cells']['lower_limit'] = null;
                $data[$key]['cells']['balance'] = null;
                $data[$key]['cells']['reserve'] = null;
                $data[$key]['cells']['packing'] = null;
                $data[$key]['cells']['weights'] = null;
                $data[$key]['cells']['volumes'] = null;

                $data[$key]['cells']['min_price'] = $item->service->min_price ?? null;
                $data[$key]['cells']['min_price_option'] = [
                    'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                ];
                $data[$key]['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);

                if (isset($item->service->suppliers)) {
                    $data[$key]['cells']['suppliers'] = $item->service->suppliers->title_supplier;
                } else {
                    $data[$key]['cells']['suppliers'] = null;
                }

                $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;

//                if (isset($item->service->organizations)) {
//                    $data[$key]['cells']['organizations'] = $item->service->organizations->title;
//                } else {
//                    $data[$key]['cells']['organizations'] = null;
//                }
            } elseif (isset($item->kit)) {
                $data[$key]['type'] = Nomenclature::KIT;
                $data[$key]['cells']['convert_id'] = $item->kit->convert_id;
                $data[$key]['cells']['short_title'] = $item->kit->short_title;
                if (isset($item->kit->dock_title)) {
                    $data[$key]['cells']['dock_title'] = $item->kit->dock_title;
                } else {
                    $data[$key]['cells']['dock_title'] = null;
                }
                $data[$key]['cells']['sku'] = $item->kit->sku;
                $data[$key]['cells']['lower_limit'] = $item->kit->lower_limit;
                $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
                $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                $data[$key]['cells']['min_price'] = $item->kit->min_price ?? null;
                $data[$key]['cells']['min_price_option'] = [
                    'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
                ];
                $data[$key]['cells']['packing'] = $item->kit->packing;
                if (isset($item->kit->units)) {
                    $data[$key]['cells']['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
                } else {
                    $data[$key]['cells']['units'] = null;
                }
                if (isset($item->kit->volume)) {
                    $data[$key]['cells']['volumes'] = $item->kit->volume;
                } else {
                    $data[$key]['cells']['volumes'] = null;
                }
                if (isset($item->kit->weight) && isset($item->kit->weights->title)) {
                    $data[$key]['cells']['weights'] = $item->kit->weight . ' ' . TitleJson::getArray($item->kit->weights->title, $this->lang);
                } else {
                    $data[$key]['cells']['weights'] = null;
                }
                if (isset($item->kit->countries) && isset($item->kit->countries->title)) {
                    $data[$key]['cells']['countries'] = TitleJson::getArray($item->kit->countries->title, $this->lang);
                } else {
                    $data[$key]['cells']['countries'] = null;
                }
                if (isset($item->kit->suppliers)) {
                    $data[$key]['cells']['supplier'] = $item->kit->suppliers->title_supplier;
                } else {
                    $data[$key]['cells']['supplier'] = null;
                }
                $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;

                if (isset($item->kits) && $item->kits->count() > 0) {
                    $data[$key]['kit_general_count'] = 0;
                    $data[$key]['kit_reserve'] = 0;
                    foreach ($item->kits as $k => $val) {
                        $data[$key]['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;

                        $data[$key]['kits'][$k]['id'] = $val->id;
                        $data[$key]['kits'][$k]['cells']['convert_id'] = $val->product->convert_id;
                        if (isset($val->product->short_title)) {
                            $data[$key]['kits'][$k]['cells']['short_title'] = $val->product->short_title;
                        } else {
                            $data[$key]['kits'][$k]['cells']['short_title'] = null;
                        }
                        $data[$key]['kits'][$k]['cells']['is_visible'] = $val->is_visible;
                        if (isset($val->product->dock_title)) {
                            $data[$key]['kits'][$k]['cells']['dock_title'] = $val->product->dock_title;
                        } else {
                            $data[$key]['kits'][$k]['cells']['dock_title'] = null;
                        }
                        if (isset($val->product->sku)) {
                            $data[$key]['kits'][$k]['cells']['sku'] = $val->product->sku;
                        } else {
                            $data[$key]['kits'][$k]['cells']['sku'] = null;
                        }

                        if (isset($val->product->lower_limit)) {
                            $data[$key]['kits'][$k]['cells']['lower_limit'] = $val->product->lower_limit;
                        } else {
                            $data[$key]['kits'][$k]['cells']['lower_limit'] = null;
                        }

                        if (isset($val->warehouses)) {
                            $data[$key]['kit_general_count'] = $data[$key]['kit_general_count'] + $val->warehouses->sum('pivot.balance');
                        }
                        if (isset($val->warehouses)) {
                            $data[$key]['kit_reserve'] = $data[$key]['kit_reserve'] + $val->warehouses->sum('pivot.reserve');
                        }

                        if (isset($val->warehouses)) {
                            $data[$key]['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve') . '/' . $val->warehouses->sum('pivot.balance');
                        }

                        $data[$key]['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve') ?? null;
                        $data[$key]['kits'][$k]['cells']['min_price'] = $val->product->min_price ?? null;

                        $data[$key]['kits'][$k]['cells']['min_price_option'] = [
                            'currency_title' => TitleJson::getArray($this->getDefaultCurrency(), $this->lang),
                        ];

                        $data[$key]['kits'][$k]['cells']['packing'] = $val->product->packing ?? null;
                        if (isset($val->product->units->title)) {
                            $data[$key]['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                        } else {
                            $data[$key]['kits'][$k]['cells']['units'] = null;
                        }
                        if (isset($val->product->volume)) {
                            $data[$key]['kits'][$k]['cells']['volumes'] = $val->product->volume;
                        } else {
                            $data[$key]['kits'][$k]['cells']['volumes'] = null;
                        }
                        if (isset($val->product->weight) && isset($val->product->weights->title)) {
                            $data[$key]['kits'][$k]['cells']['weights'] = $val->product->weight . ' ' . TitleJson::getArray($val->product->weights->title, $this->lang);
                        } else {
                            $data[$key]['kits'][$k]['cells']['weights'] = null;
                        }
                        if (isset($val->product->countries) && isset($val->product->countries->title)) {
                            $data[$key]['kits'][$k]['cells']['countries'] = TitleJson::getArray($val->product->countries->title, $this->lang);
                        } else {
                            $data[$key]['kits'][$k]['cells']['countries'] = null;
                        }
                        if (isset($val->product->suppliers)) {
                            $data[$key]['kits'][$k]['cells']['supplier'] = $val->product->suppliers->title_supplier;
                        } else {
                            $data[$key]['kits'][$k]['cells']['supplier'] = null;
                        }

                        //$data[$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                        for ($i = 1; $i <= $prices->count(); $i++) {
                            $data[$key]['kits'][$k]['cells']['price_' . $i] = null;
                            $data[$key]['kits'][$k]['cells']['price_' . $i . '_option'] = [
                                'id' => null,
                                'is_default' => null,
                                'is_rounding_without' => null,
                                'is_rounding_with' => null,
                                'currency_title' => null,
                            ];
                        }

                        foreach ($val->prices->unique('id') as $price) {
                            $data[$key]['kits'][$k]['cells']['price_' . $price->id] = $price->pivot->value;

                            $data[$key]['kits'][$k]['cells']['price_' . $price->id . '_option'] = [
                                'id' => $price->pivot->id,
                                'is_default' => $price->is_default,
                                'is_rounding_without' => $price->is_rounding_without,
                                'is_rounding_with' => $price->is_rounding_with,
                                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                            ];
                        }
                        $data[$key]['kits'][$k]['cells']['basket_orders_count'] = $val->basketOrders->count ?? 0;
                    }
                    $data[$key]['cells']['possible_balance_kits'] = (int)collect($data[$key]['kits'])->min('count_kit');
                    $data[$key]['cells']['balance'] = $data[$key]['kit_general_count'] - $data[$key]['kit_reserve'] . '/' . $data[$key]['kit_general_count'];
                } else {
                    $data[$key]['kits'] = [];
                }
            }

            $data[$key]['cells']['is_visible'] = $item->is_visible;

            for ($i = 1; $i <= $prices->count(); $i++) {
                $data[$key]['cells']['price_' . $i] = null;

                $data[$key]['cells']['price_' . $i . '_option'] = [
                    'id' => null,
                    'is_default' => null,
                    'is_rounding_without' => null,
                    'is_rounding_with' => null,
                    'currency_title' => null,
                ];
            }

            foreach ($item->prices->unique('id') as $price) {
                $data[$key]['cells']['price_' . $price->id] = $price->pivot->value;

                $data[$key]['cells']['price_' . $price->id . '_option'] = [
                    'id' => $price->pivot->id,
                    'is_default' => $price->is_default,
                    'is_rounding_without' => $price->is_rounding_without,
                    'is_rounding_with' => $price->is_rounding_with,
                    'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                ];
            }
        }
        return $data;
    }

    public function getProductsTable($products, $prices)
    {
        $data = [];
        foreach ($products as $key => $item) {

            $data[$key]['id'] = $item->id;
            //$data[$key]['cells']['id'] = $item->id;
            $data[$key]['cells']['convert_id'] = $item->product->convert_id;
            $data[$key]['cells']['short_title'] = $item->product->short_title;
            $data[$key]['cells']['is_visible'] = $item->is_visible;
            if (isset($item->product->dock_title)) {
                $data[$key]['cells']['dock_title'] = $item->product->dock_title;
            } else {
                $data[$key]['cells']['dock_title'] = null;
            }
            //$data[$key]['cells']['desc'] = $value->desc;
            $data[$key]['cells']['sku'] = $item->product->sku;
            $data[$key]['cells']['lower_limit'] = $item->product->lower_limit;
            //$data[$key]['cells']['general_count'] = $value->general_count;
            $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
            $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
            $data[$key]['cells']['min_price'] = $item->product->min_price ?? null;
            $data[$key]['cells']['min_price_option'] = [
                'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
            ];
//                $object[$key]['cells']['barcode_supplier'] = $value->barcode_supplier;
//                $object[$key]['cells']['barcode'] = $value->barcode;
            $data[$key]['cells']['packing'] = $item->product->packing;
            if (isset($item->product->units)) {
                $data[$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
            } else {
                $data[$key]['cells']['units'] = null;
            }
            if (isset($item->product->volume)) {
                $data[$key]['cells']['volumes'] = $item->product->volume;
            } else {
                $data[$key]['cells']['volumes'] = null;
            }
            if (isset($item->product->weight) && isset($item->product->weights->title)) {
                $data[$key]['cells']['weights'] = $item->product->weight . ' ' . TitleJson::getArray($item->product->weights->title, $this->lang);
            } else {
                $data[$key]['cells']['weights'] = null;
            }
            if (isset($item->product->countries) && isset($item->product->countries->title)) {
                $data[$key]['cells']['countries'] = TitleJson::getArray($item->product->countries->title, $this->lang);
            } else {
                $data[$key]['cells']['countries'] = null;
            }
            if (isset($item->product->suppliers)) {
                $data[$key]['cells']['supplier'] = $item->product->suppliers->title_supplier;
            } else {
                $data[$key]['cells']['supplier'] = null;
            }

            for ($i = 1; $i <= $prices->count(); $i++) {
                $data[$key]['cells']['price_' . $i] = null;

                $data[$key]['cells']['price_' . $i . '_option'] = [
                    'id' => null,
                    'is_default' => null,
                    'is_rounding_without' => null,
                    'is_rounding_with' => null,
                    'currency_title' => null,
                ];
            }

            foreach ($item->prices->unique('id') as $price) {
                $data[$key]['cells']['price_' . $price->id] = $price->pivot->value;

                $data[$key]['cells']['price_' . $price->id . '_option'] = [
                    'id' => $price->pivot->id,
                    'is_default' => $price->is_default,
                    'is_rounding_without' => $price->is_rounding_without,
                    'is_rounding_with' => $price->is_rounding_with,
                    'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                ];
            }

            $data[$key]['is_groups'] = $item->product->is_groups;
            $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;

            if (isset($item->groups) && $item->groups->count() > 0) {

                // $data['groups'] = $item->groups;
                //dd($item->groups);

                $data[$key]['group_general_count'] = 0;
                $data[$key]['group_reserve'] = 0;
                foreach ($item->groups as $k => $group) {

                    $data[$key]['groups'][$k]['id'] = $group->id;
                    $data[$key]['groups'][$k]['cells']['convert_id'] = $group->product->convert_id ?? null;
                    if (isset($group->product->short_title)) {
                        $data[$key]['groups'][$k]['cells']['short_title'] = $group->product->short_title;
                    } else {
                        $data[$key]['groups'][$k]['cells']['short_title'] = null;
                    }

                    $data[$key]['groups'][$k]['cells']['is_visible'] = $group->is_visible;
                    if (isset($group->product->dock_title)) {
                        $data[$key]['groups'][$k]['cells']['dock_title'] = $group->product->dock_title;
                    } else {
                        $data[$key]['groups'][$k]['cells']['dock_title'] = null;
                    }
                    if (isset($group->product->sku)) {
                        $data[$key]['groups'][$k]['cells']['sku'] = $group->product->sku;
                    } else {
                        $data[$key]['groups'][$k]['cells']['sku'] = null;
                    }

                    if (isset($group->product->lower_limit)) {
                        $data[$key]['groups'][$k]['cells']['lower_limit'] = $group->product->lower_limit;
                    } else {
                        $data[$key]['groups'][$k]['cells']['lower_limit'] = null;
                    }
                    //$data[$key]['groups'][$k]['lower_limit'] = $group->product->lower_limit;
                    //$data[$key]['cells']['general_count'] = $value->general_count;
                    //$item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve')
                    if (isset($group->warehouses)) {
                        $data[$key]['group_general_count'] = $data[$key]['group_general_count'] + $group->warehouses->sum('pivot.balance');
                    }
                    if (isset($group->warehouses)) {
                        $data[$key]['group_reserve'] = $data[$key]['group_reserve'] + $group->warehouses->sum('pivot.reserve');
                    }

                    if (isset($group->warehouses)) {
                        $data[$key]['groups'][$k]['cells']['balance'] = $group->warehouses->sum('pivot.balance') - $group->warehouses->sum('pivot.reserve') . '/' . $group->warehouses->sum('pivot.balance');
                    }

                    $data[$key]['groups'][$k]['cells']['reserve'] = $group->warehouses->sum('pivot.reserve') ?? null;
                    $data[$key]['groups'][$k]['cells']['min_price'] = $group->product->min_price ?? null;

                    $data[$key]['groups'][$k]['cells']['min_price_option'] = [
                        'currency_title' => TitleJson::getArray($this->getDefaultCurrency(), $this->lang),
                    ];

//                $object[$key]['cells']['barcode_supplier'] = $value->barcode_supplier;
//                $object[$key]['cells']['barcode'] = $value->barcode;
                    $data[$key]['groups'][$k]['cells']['packing'] = $group->product->packing ?? null;
                    if (isset($group->product->units->title)) {
                        $data[$key]['groups'][$k]['cells']['units'] = TitleJson::getArray($group->product->units->title, $this->lang);
                    } else {
                        $data[$key]['groups'][$k]['cells']['units'] = null;
                    }
                    if (isset($group->product->volume)) {
                        $data[$key]['groups'][$k]['cells']['volumes'] = $group->product->volume;
                    } else {
                        $data[$key]['groups'][$k]['cells']['volumes'] = null;
                    }
                    if (isset($group->product->weight) && isset($group->product->weights->title)) {
                        $data[$key]['groups'][$k]['cells']['weights'] = $group->product->weight . ' ' . TitleJson::getArray($group->product->weights->title, $this->lang);
                    } else {
                        $data[$key]['groups'][$k]['cells']['weights'] = null;
                    }
                    if (isset($group->product->countries) && isset($group->product->countries->title)) {
                        $data[$key]['groups'][$k]['cells']['countries'] = TitleJson::getArray($group->product->countries->title, $this->lang);
                    } else {
                        $data[$key]['groups'][$k]['cells']['countries'] = null;
                    }
                    if (isset($group->product->suppliers)) {
                        $data[$key]['groups'][$k]['cells']['supplier'] = $group->product->suppliers->title_supplier;
                    } else {
                        $data[$key]['groups'][$k]['cells']['supplier'] = null;
                    }

                    //$data[$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                    for ($i = 1; $i <= $prices->count(); $i++) {
                        $data[$key]['groups'][$k]['cells']['price_' . $i] = null;
                        $data[$key]['groups'][$k]['cells']['price_' . $i . '_option'] = [
                            'id' => null,
                            'is_default' => null,
                            'is_rounding_without' => null,
                            'is_rounding_with' => null,
                            'currency_title' => null,
                        ];
                    }

                    foreach ($group->prices->unique('id') as $price) {
                        $data[$key]['groups'][$k]['cells']['price_' . $price->id] = $price->pivot->value;

                        $data[$key]['groups'][$k]['cells']['price_' . $price->id . '_option'] = [
                            'id' => $price->pivot->id,
                            'is_default' => $price->is_default,
                            'is_rounding_without' => $price->is_rounding_without,
                            'is_rounding_with' => $price->is_rounding_with,
                            'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                        ];
                    }
                    $data[$key]['groups'][$k]['cells']['basket_orders_count'] = $group->basketOrders->count ?? 0;
                }
                $data[$key]['balance'] = $data[$key]['group_general_count'] - $data[$key]['group_reserve'] . '/' . $data[$key]['group_general_count'];
                $data[$key]['cells']['balance'] = $data[$key]['group_general_count'] - $data[$key]['group_reserve'] . '/' . $data[$key]['group_general_count'];
            } else {
                $data[$key]['groups'] = [];
            }
        }
        return $data;
    }

    public function getKitsTable($items, $prices)
    {
        $data = [];
        foreach ($items as $key => $item) {

            $data[$key]['id'] = $item->id;
            //$data[$key]['cells']['id'] = $item->id;
            $data[$key]['cells']['convert_id'] = $item->kit->convert_id;
            $data[$key]['cells']['short_title'] = $item->kit->short_title;
            $data[$key]['cells']['is_visible'] = $item->is_visible;
            if (isset($item->kit->dock_title)) {
                $data[$key]['cells']['dock_title'] = $item->kit->dock_title;
            } else {
                $data[$key]['cells']['dock_title'] = null;
            }
            //$data[$key]['cells']['desc'] = $value->desc;
            $data[$key]['cells']['sku'] = $item->kit->sku;
            $data[$key]['cells']['lower_limit'] = $item->kit->lower_limit;
            //$data[$key]['cells']['general_count'] = $value->general_count;
            $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
            $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
            $data[$key]['cells']['min_price'] = $item->kit->min_price ?? null;
            $data[$key]['cells']['min_price_option'] = [
                'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
            ];
//                $object[$key]['cells']['barcode_supplier'] = $value->barcode_supplier;
//                $object[$key]['cells']['barcode'] = $value->barcode;
            $data[$key]['cells']['packing'] = $item->kit->packing;
            if (isset($item->kit->units)) {
                $data[$key]['cells']['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
            } else {
                $data[$key]['cells']['units'] = null;
            }
            if (isset($item->kit->volume)) {
                $data[$key]['cells']['volumes'] = $item->kit->volume;
            } else {
                $data[$key]['cells']['volumes'] = null;
            }
            if (isset($item->kit->weight) && isset($item->kit->weights->title)) {
                $data[$key]['cells']['weights'] = $item->kit->weight . ' ' . TitleJson::getArray($item->kit->weights->title, $this->lang);
            } else {
                $data[$key]['cells']['weights'] = null;
            }
            if (isset($item->kit->countries) && isset($item->kit->countries->title)) {
                $data[$key]['cells']['countries'] = TitleJson::getArray($item->kit->countries->title, $this->lang);
            } else {
                $data[$key]['cells']['countries'] = null;
            }
            if (isset($item->kit->suppliers)) {
                $data[$key]['cells']['supplier'] = $item->kit->suppliers->title_supplier;
            } else {
                $data[$key]['cells']['supplier'] = null;
            }

            for ($i = 1; $i <= $prices->count(); $i++) {
                $data[$key]['cells']['price_' . $i] = null;

                $data[$key]['cells']['price_' . $i . '_option'] = [
                    'id' => null,
                    'is_default' => null,
                    'is_rounding_without' => null,
                    'is_rounding_with' => null,
                    'currency_title' => null,
                ];
            }
            foreach ($item->prices->unique('id') as $price) {
                $data[$key]['cells']['price_' . $price->id] = $price->pivot->value;

                $data[$key]['cells']['price_' . $price->id . '_option'] = [
                    'id' => $price->pivot->id,
                    'is_default' => $price->is_default,
                    'is_rounding_without' => $price->is_rounding_without,
                    'is_rounding_with' => $price->is_rounding_with,
                    'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                ];
            }
            $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;

            if (isset($item->kits) && $item->kits->count() > 0) {
                $data[$key]['kit_general_count'] = 0;
                $data[$key]['kit_reserve'] = 0;
                foreach ($item->kits as $k => $val) {
                    //dd($val->pivot);
                    $data[$key]['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;
                    $data[$key]['kits'][$k]['id'] = $val->id;
                    $data[$key]['kits'][$k]['cells']['convert_id'] = $val->product->convert_id;
                    if (isset($val->product->short_title)) {
                        $data[$key]['kits'][$k]['cells']['short_title'] = $val->product->short_title;
                    } else {
                        $data[$key]['kits'][$k]['cells']['short_title'] = null;
                    }

                    $data[$key]['kits'][$k]['cells']['is_visible'] = $val->is_visible;
                    if (isset($val->product->dock_title)) {
                        $data[$key]['kits'][$k]['cells']['dock_title'] = $val->product->dock_title;
                    } else {
                        $data[$key]['kits'][$k]['cells']['dock_title'] = null;
                    }
                    if (isset($val->product->sku)) {
                        $data[$key]['kits'][$k]['cells']['sku'] = $val->product->sku;
                    } else {
                        $data[$key]['kits'][$k]['cells']['sku'] = null;
                    }

                    if (isset($val->product->lower_limit)) {
                        $data[$key]['kits'][$k]['cells']['lower_limit'] = $val->product->lower_limit;
                    } else {
                        $data[$key]['kits'][$k]['cells']['lower_limit'] = null;
                    }

                    if (isset($val->warehouses)) {
                        $data[$key]['kit_general_count'] = $data[$key]['kit_general_count'] + $val->warehouses->sum('pivot.balance');
                    }
                    if (isset($val->warehouses)) {
                        $data[$key]['kit_reserve'] = $data[$key]['kit_reserve'] + $val->warehouses->sum('pivot.reserve');
                    }

                    if (isset($val->warehouses)) {
                        $data[$key]['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve') . '/' . $val->warehouses->sum('pivot.balance');
                    }

                    $data[$key]['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve') ?? null;
                    $data[$key]['kits'][$k]['cells']['min_price'] = $val->product->min_price ?? null;

                    $data[$key]['kits'][$k]['cells']['min_price_option'] = [
                        'currency_title' => TitleJson::getArray($this->getDefaultCurrency(), $this->lang),
                    ];

//                $object[$key]['cells']['barcode_supplier'] = $value->barcode_supplier;
//                $object[$key]['cells']['barcode'] = $value->barcode;
                    $data[$key]['kits'][$k]['cells']['packing'] = $val->product->packing ?? null;
                    if (isset($val->product->units->title)) {
                        $data[$key]['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                    } else {
                        $data[$key]['kits'][$k]['cells']['units'] = null;
                    }
                    if (isset($val->product->volume)) {
                        $data[$key]['kits'][$k]['cells']['volumes'] = $val->product->volume;
                    } else {
                        $data[$key]['kits'][$k]['cells']['volumes'] = null;
                    }
                    if (isset($val->product->weight) && isset($val->product->weights->title)) {
                        $data[$key]['kits'][$k]['cells']['weights'] = $val->product->weight . ' ' . TitleJson::getArray($val->product->weights->title, $this->lang);
                    } else {
                        $data[$key]['kits'][$k]['cells']['weights'] = null;
                    }
                    if (isset($val->product->countries) && isset($val->product->countries->title)) {
                        $data[$key]['kits'][$k]['cells']['countries'] = TitleJson::getArray($val->product->countries->title, $this->lang);
                    } else {
                        $data[$key]['kits'][$k]['cells']['countries'] = null;
                    }
                    if (isset($val->product->suppliers)) {
                        $data[$key]['kits'][$k]['cells']['supplier'] = $val->product->suppliers->title_supplier;
                    } else {
                        $data[$key]['kits'][$k]['cells']['supplier'] = null;
                    }

                    //$data[$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                    for ($i = 1; $i <= $prices->count(); $i++) {
                        $data[$key]['kits'][$k]['cells']['price_' . $i] = null;
                        $data[$key]['kits'][$k]['cells']['price_' . $i . '_option'] = [
                            'id' => null,
                            'is_default' => null,
                            'is_rounding_without' => null,
                            'is_rounding_with' => null,
                            'currency_title' => null,
                        ];
                    }

                    foreach ($val->prices->unique('id') as $price) {
                        $data[$key]['kits'][$k]['cells']['price_' . $price->id] = $price->pivot->value;

                        $data[$key]['kits'][$k]['cells']['price_' . $price->id . '_option'] = [
                            'id' => $price->pivot->id,
                            'is_default' => $price->is_default,
                            'is_rounding_without' => $price->is_rounding_without,
                            'is_rounding_with' => $price->is_rounding_with,
                            'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                        ];
                    }
                    $data[$key]['kits'][$k]['cells']['basket_orders_count'] = $val->basketOrders->count ?? 0;
                }
                $data[$key]['balance'] = $data[$key]['kit_general_count'] - $data[$key]['kit_reserve'] . '/' . $data[$key]['kit_general_count'];
                $data[$key]['cells']['balance'] = $data[$key]['kit_general_count'] - $data[$key]['kit_reserve'] . '/' . $data[$key]['kit_general_count'];

                $data[$key]['cells']['possible_balance_kits'] = (int)collect($data[$key]['kits'])->min('count_kit');

            } else {
                $data[$key]['kits'] = [];
            }
        }
        return $data;
    }

    public function getServicesTable($services, $prices)
    {
        $data = [];
        foreach ($services as $key => $item) {

            $data[$key]['id'] = $item->id;
            $data[$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
            $data[$key]['cells']['short_title'] = $item->service->short_title;
            $data[$key]['cells']['is_visible'] = $item->is_visible;
            if (isset($item->service->dock_title)) {
                $data[$key]['cells']['dock_title'] = $item->service->dock_title;
            } else {
                $data[$key]['cells']['dock_title'] = null;
            }
            $data[$key]['cells']['sku'] = $item->service->sku;

            $data[$key]['cells']['min_price'] = $item->service->min_price ?? null;
            $data[$key]['cells']['min_price_option'] = [
                'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
            ];

            $data[$key]['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);

            $data[$key]['cells']['unit_id'] = $item->service->unit_id ?? null;
            $data[$key]['cells']['supplier_id'] = $item->service->supplier_id ?? null;
            $data[$key]['cells']['organization_id'] = $item->service->organization_id ?? null;

            if (isset($item->service->suppliers)) {
                $data[$key]['cells']['suppliers'] = $item->service->suppliers->title_supplier;
            } else {
                $data[$key]['cells']['suppliers'] = null;
            }

//            if (isset($item->service->organizations)) {
//                $data[$key]['cells']['organizations'] = $item->service->organizations->title;
//            } else {
//                $data[$key]['cells']['organizations'] = null;
//            }

            for ($i = 1; $i <= $prices->count(); $i++) {
                $data[$key]['cells']['price_' . $i] = null;

                $data[$key]['cells']['price_' . $i . '_option'] = [
                    'id' => null,
                    'is_default' => null,
                    'is_rounding_without' => null,
                    'is_rounding_with' => null,
                    'currency_title' => null,

                ];
            }
            foreach ($item->prices->unique('id') as $price) {
                $data[$key]['cells']['price_' . $price->id] = $price->pivot->value;

                $data[$key]['cells']['price_' . $price->id . '_option'] = [
                    'id' => $price->pivot->id,
                    'is_default' => $price->is_default,
                    'is_rounding_without' => $price->is_rounding_without,
                    'is_rounding_with' => $price->is_rounding_with,
                    'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                ];
            }
            $data[$key]['cells']['basket_orders_count'] = $item->basketOrders->count ?? 0;

            //$data[$key]['is_groups'] = $item->service->is_groups;
        }
        return $data;
    }

    public function getProductUpdate($item, $prices)
    {
        $data = [];

        //dd($item);
        $data['id'] = $item->id;
        //$data['nomenclature_id'] = $item->id;
        $data['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);

        $data['is_visible'] = $item->is_visible;
        $data['is_actual'] = $item->is_actual;
        $data['created_at'] = $item->product->created_at;
        $data['updated_at'] = $item->product->updated_at;
        $data['product_id'] = $item->product->id;
        $data['short_title'] = $item->product->short_title;
        $data['dock_title'] = $item->product->dock_title;
        $data['desc'] = $item->product->desc;
        $data['sku'] = $item->product->sku;
        $data['lower_limit'] = $item->product->lower_limit;

        if (isset($item->product->weight)) {
            $data['weight'] = $item->product->weight;
        } else {
            $data['weight'] = null;
        }

//        dd($item->product->volumes);
        if (isset($item->product->volume)) {
            $data['volume'] = $item->product->volume;
        } else {
            $data['volume'] = null;
        }

        $data['general_count'] = $item->warehouses->sum('pivot.balance');
        $data['reserve'] = $item->warehouses->sum('pivot.reserve');
        $data['barcode_supplier'] = $item->product->barcode_supplier;
        $data['barcode'] = $item->product->barcode;
        $data['code'] = $item->product->code;
        $data['packing'] = $item->product->packing;
        $data['min_price'] = $item->product->min_price;
        $data['min_price_option'] = [
            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
        ];
        $data['identifier_fullness'] = $item->product->identifier_fullness;
        $data['identifier_successful'] = $item->product->identifier_successful;
        if (isset($item->product->units)) {
            $data['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
        }
        $data['unit_id'] = $item->product->unit_id;
        $data['manufacturer_id'] = $item->product->manufacturer_id;
        $data['supplier'] = $item->product->suppliers->title_supplier ?? null;
        $data['supplier_id'] = $item->product->supplier_id;
        $data['weight_id'] = $item->product->weight_id;
        $data['volume_id'] = $item->product->volume_id;
        $data['country_id'] = $item->product->country_id;
        $data['category_id'] = $item->category_id;
        $data['prices'] = array();

        foreach ($item->prices->unique('id') as $price) {

            $data['prices'][] = [
                'is_buy' => $price->is_buy,
                'title' => TitleJson::getArray($price->title, $this->lang),
                'type_price_id' => $price->id,
                'id' => $price->pivot->id,
                'value' => $price->pivot->value,
                'is_default' => $price->is_default,
                'is_editable' => $price->is_editable,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id ?? [], $this->lang),
                'symbol_currency' => TitleJson::getArray($price->symbol_currency ?? [], $this->lang),
            ];
        }
//        foreach ($item->prices as $price) {
//            $data['history_prices'][] = [
//                'title' => TitleJson::getArray($price->title, $this->lang),
//                'type_price_id' => $price->id,
//                'id' => $price->pivot->id,
//                'value' => $price->pivot->value,
//                'is_default' => $price->is_default,
//                'is_editable' => $price->is_editable,
//                'is_rounding_without' => $price->is_rounding_without,
//                'is_rounding_with' => $price->is_rounding_with,
//                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
//            ];
//        }
        $data['characteristic_color_value'] = null;
        $data['characteristic_value'] = [];

        $characteristic_value = [];
        $data['property_value'] = $item->propertyValue;

        if (count($item->characteristic) > 0) {
            foreach ($item->characteristic as $key => $value) {
                if (count($value->characteristicColorValue) > 0) {
                    $data['characteristic_color_value'] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'is_base' => $value->pivot->is_base
                    ];
                    $data['characteristic_color_value']['values'] = [];

//                    foreach ($value->characteristicColorValue as $val) {
//                        $data['characteristic_color_value']['values'][] = [
//                            'id' => $val->id,
//                            'title' => $val->title,
//                            'code' => $val->code,
//                            'image' => $val->image,
//                            'color' => $val->color,
//                        ];
//                    }
                } else {
                    $characteristic_value[$value->id] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'is_base' => $value->pivot->is_base
                    ];
                    $characteristic_value[$value->id]['values'] = [];
//                    foreach ($value->characteristicValue as $val) {
//
//                        $characteristic_value[$value->id]['values'][] = [
//                            'id' => $val->id,
//                            'title' => $val->title,
//                        ];
//                    }
                }
            }

        } else {

            foreach ($item->characteristicValue as $key => $value) {

                $characteristic_value[$value['characteristic_id']] = [
                    'id' => $value->characteristic->id,
                    'title' => $value->characteristic->title,
                    'is_base' => $value->pivot->is_base
                ];

                $characteristic_value[$value['characteristic_id']]['values'][] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'edit' => $value->edit,
                ];
            }

            foreach ($characteristic_value as $value) {
                $data['characteristic_value'][] = $value;
            }

            foreach ($item->characteristicColorValue as $value) {
                $data['characteristic_color_value'] = [
                    'id' => $value->characteristic->id,
                    'title' => $value->characteristic->title,
                    'is_base' => $value->pivot->is_base
                ];
                if ($item->is_groups == false) {
                    $data['characteristic_color_value']['values'] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'code' => $value->code,
                        'image' => $value->image,
                        'color' => $value->color,
                    ];
                } else {
                    $data['characteristic_color_value']['values'][] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'code' => $value->code,
                        'image' => $value->image,
                        'color' => $value->color,
                    ];
                }
            }
        }

        $data['categories'] = $item->categories;
        $data['category'] = $item->category;
//        $data['related'] = $item->related;
//        $data['related']['headers'] = TitleJson::get(tableRelatedNomenclaturesInNomenclatureHeader::get(), $this->lang);
//        $data['related']['headers']['body'] =

        $data['is_groups'] = $item->product->is_groups;
        $data['groups'] = [];
        if (isset($item->groups) && $item->groups->count() > 0) {

            foreach ($item->groups as $key => $group) {

                $data['groups'][$key]['cells']['short_title'] = $group->product->short_title ?? null;
                $data['groups'][$key]['id'] = $group->id;
                $data['groups'][$key]['cells']['convert_id'] = str_pad($group->id, 8, '0', STR_PAD_LEFT);
                $data['groups'][$key]['cells']['sku'] = $group->product->sku ?? null;

                if (isset($group->prices) && $group->prices->count() > 0) {
                    for ($i = 1; $i <= $prices->count(); $i++) {
                        $data['groups'][$key]['cells']['price_' . $i] = null;

                        $data['groups'][$key]['cells']['price_' . $i . '_option'] = [
                            'id' => null,
                            'is_default' => null,
                            'is_rounding_without' => null,
                            'is_rounding_with' => null,
                            'currency_title' => null,
                        ];

//                        $data['groups'][$key]['price_is_default_' . $i] = null;
//                        $data['groups'][$key]['price_is_rounding_without_' . $i] = null;
//                        $data['groups'][$key]['price_is_rounding_with_' . $i] = null;
                    }
                    foreach ($group->prices as $price) {
                        $data['groups'][$key]['cells']['price_' . $price->id] = $price->pivot->value;
                        $data['groups'][$key]['cells']['price_' . $price->id . '_option'] = [
                            'id' => $price->pivot->id,
                            'is_default' => $price->is_default,
                            'is_rounding_without' => $price->is_rounding_without,
                            'is_rounding_with' => $price->is_rounding_with,
                            'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),

                        ];
                    }
                }

                if (count($group->characteristicColorValue) > 0) {
                    foreach ($group->characteristicColorValue as $val) {
                        $arrayCharacteristicColorValue['values'][$val->id] = [
                            'id' => $val->id,
                            'title' => $val->title,
                            'code' => $val->code,
                            'image' => $val->image,
                            'color' => $val->color,
                        ];
                    }
                    //dd($array['values']);
                    //$data['characteristic_color_value']['values'] =  (array)$data['characteristic_color_value']['values'];
                }
                if (count($group->characteristicValue) > 0) {
                    foreach ($group->characteristicValue as $key => $value) {
//                    $characteristic_value[$value['characteristic_id']] = [
//                        'id' => $value->characteristic->id,
//                        'title' => $value->characteristic->title,
//                        'is_base' => $value->pivot->is_base
//                    ];
                        $characteristic_value[$value['characteristic_id']]['tmp'][$value['id']] = [
                            'id' => $value->id,
                            'title' => $value->title,
                            'edit' => $value->edit,
                        ];
                    }
                    foreach ($characteristic_value as $value) {
                        if (isset($value['id'])) {

                            $arrayCharacteristicValue[$value['id']] = $value;
                            if (isset($arrayCharacteristicValue[$value['id']]['tmp'])) {
                                foreach ($arrayCharacteristicValue[$value['id']]['tmp'] as $v) {
                                    $arrayCharacteristicValue[$value['id']]['values'][] = $v;
                                }
                            }

                            //$data['characteristic_value'][$value['id']] = $value;
                        }
                    }

                }
            }

            if (isset($arrayCharacteristicColorValue)) {
                foreach ($arrayCharacteristicColorValue['values'] as $value) {
                    $data['characteristic_color_value']['values'][] = $value;
                }
            }
            if (isset($arrayCharacteristicValue)) {
                foreach ($arrayCharacteristicValue as $value) {
                    $data['characteristic_value'][] = $value;
                }
            }
        } elseif (isset($characteristic_value) && $item->product->is_groups == true && $item->groups->count() == 0) {
            foreach ($characteristic_value as $v) {
                $data['characteristic_value'][] = $v;
            }

        }
        //dd($item->files);
        $data['images'] = [];
        $data['files'] = [];
        if (isset($item->files)) {
            foreach ($item->files as $file) {
                if ($file->is_image) {
                    $data['images'][] = $file;
                } else {
                    $data['files'][] = $file;
                }
            }
        }
//        else{
//            if(isset($characteristic_value)){
//                foreach ($characteristic_value as $v) {
//                    $data['characteristic_value'][] = $v;
//                }
//            }
//        }
        //dd($data['characteristic_value']);

        //$data['characteristic_value_update'] = array_unique($data['characteristic_value']);

        //$product['prices'][$key]['title'] = $price->pivot->value . ' '.  TitleJson::getArray($price->title, $this->lang);
        return $data;
    }

    public function getKitUpdate($item, $prices)
    {
        $data = [];
        $data['id'] = $item->id;
        $data['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);

        $data['is_visible'] = $item->is_visible;
        $data['is_actual'] = $item->is_actual;
        $data['created_at'] = $item->kit->created_at;
        $data['updated_at'] = $item->kit->updated_at;
        $data['product_id'] = $item->kit->id;
        $data['short_title'] = $item->kit->short_title;
        $data['dock_title'] = $item->kit->dock_title;
        $data['desc'] = $item->kit->desc;
        $data['sku'] = $item->kit->sku;
        $data['lower_limit'] = $item->kit->lower_limit;

        if (isset($item->kit->weight)) {
            $data['weight'] = $item->kit->weight;
        } else {
            $data['weight'] = null;
        }

        if (isset($item->kit->volume)) {
            $data['volume'] = $item->kit->volume;
        } else {
            $data['volume'] = null;
        }

        $data['general_count'] = $item->warehouses->sum('pivot.balance');
        $data['reserve'] = $item->warehouses->sum('pivot.reserve');
        $data['code'] = $item->kit->code;
        $data['packing'] = $item->kit->packing;
        $data['min_price'] = $item->kit->min_price;
        $data['min_price_option'] = [
            'currency_title' => TitleJson::getArray($this->getDefaultCurrency()->title, $this->lang),
        ];
        $data['identifier_fullness'] = $item->kit->identifier_fullness;
        $data['identifier_successful'] = $item->kit->identifier_successful;
        if (isset($item->kit->units)) {
            $data['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
        }
        $data['unit_id'] = $item->kit->unit_id;
        $data['manufacturer_id'] = $item->kit->manufacturer_id;
        $data['supplier'] = $item->kit->suppliers->title_supplier ?? null;
        $data['supplier_id'] = $item->kit->supplier_id;
        $data['weight_id'] = $item->kit->weight_id;
        $data['volume_id'] = $item->kit->volume_id;
        $data['country_id'] = $item->kit->country_id;
        $data['category_id'] = $item->category_id;
        $data['prices'] = array();

        foreach ($item->prices->unique('id') as $price) {

            $data['prices'][] = [
                'is_buy' => $price->is_buy,
                'title' => TitleJson::getArray($price->title, $this->lang),
                'type_price_id' => $price->id,
                'id' => $price->pivot->id,
                'value' => $price->pivot->value,
                'is_default' => $price->is_default,
                'is_editable' => $price->is_editable,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id ?? [], $this->lang),
                'symbol_currency' => TitleJson::getArray($price->symbol_currency ?? [], $this->lang),
            ];
        }

        $data['characteristic_color_value'] = null;
        $data['characteristic_value'] = [];

        $characteristic_value = [];
        $data['property_value'] = $item->propertyValue;

        if (count($item->characteristic) > 0) {
            foreach ($item->characteristic as $key => $value) {
                if (count($value->characteristicColorValue) > 0) {
                    $data['characteristic_color_value'] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'is_base' => $value->pivot->is_base
                    ];
                    $data['characteristic_color_value']['values'] = [];

                } else {
                    $characteristic_value[$value->id] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'is_base' => $value->pivot->is_base
                    ];
                    $characteristic_value[$value->id]['values'] = [];
                }
            }
        } else {
            foreach ($item->characteristicValue as $key => $value) {

                $characteristic_value[$value['characteristic_id']] = [
                    'id' => $value->characteristic->id,
                    'title' => $value->characteristic->title,
                    'is_base' => $value->pivot->is_base
                ];

                $characteristic_value[$value['characteristic_id']]['values'][] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'edit' => $value->edit,
                ];
            }

            foreach ($characteristic_value as $value) {
                $data['characteristic_value'][] = $value;
            }

            foreach ($item->characteristicColorValue as $value) {
                $data['characteristic_color_value'] = [
                    'id' => $value->characteristic->id,
                    'title' => $value->characteristic->title,
                    'is_base' => $value->pivot->is_base
                ];
                if ($item->groups->count() == 0) {
                    $data['characteristic_color_value']['values'] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'code' => $value->code,
                        'image' => $value->image,
                        'color' => $value->color,
                    ];
                } else {
                    $data['characteristic_color_value']['values'][] = [
                        'id' => $value->id,
                        'title' => $value->title,
                        'code' => $value->code,
                        'image' => $value->image,
                        'color' => $value->color,
                    ];
                }
            }
        }
        $data['categories'] = $item->categories;
        $data['category'] = $item->category;
        $data['images'] = [];
        $data['files'] = [];

        if (isset($item->files)) {
            foreach ($item->files as $file) {
                if ($file->is_image) {
                    $data['images'][] = $file;
                } else {
                    $data['files'][] = $file;
                }
            }
        }

        $data['kits'] = [];
        if (isset($item->kits) && $item->kits->count() > 0) {
            $data['kits']['headers'] = TitleJson::get(KitsTableHeader::get(), $this->lang);
            foreach ($item->kits as $key => $kit) {
                $data['kits']['body'][$key]['count_kit'] = ($kit->warehouses->sum('pivot.balance') - $kit->warehouses->sum('pivot.reserve')) / $kit->pivot->count;
                $data['kits']['body'][$key]['id'] = $kit->id;
                $data['kits']['body'][$key]['cells']['convert_id'] = str_pad($kit->id, 8, '0', STR_PAD_LEFT);
                $data['kits']['body'][$key]['cells']['sku'] = $kit->product->sku ?? null;
                $data['kits']['body'][$key]['cells']['short_title'] = $kit->product->short_title;
                $data['kits']['body'][$key]['cells']['units'] = TitleJson::getArray($kit->product->units->title, $this->lang);
                if (isset($kit->prices)) {
                    foreach ($kit->prices->unique('id') as $price) {
                        $data['kits']['body'][$key]['cells']['price'] = $price->pivot->value;
//                        if (isset($price->pivot->value)) {
//                            $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
//                        }
                    }
                }
                if (count($kit->warehouses) > 0) {
                    $data['kits']['body'][$key]['cells']['balance'] = $kit->warehouses->sum('pivot.balance') - $kit->warehouses->sum('pivot.reserve') . '/' . $kit->warehouses->sum('pivot.balance');
                    $data['kits']['body'][$key]['cells']['reserve'] = $kit->warehouses->sum('pivot.reserve');
                } else {
                    $data['kits']['body'][$key]['cells']['reserve'] = null;
                    $data['kits']['body'][$key]['cells']['balance'] = null;
                }
                $data['kits']['body'][$key]['cells']['count'] = $kit->pivot->count;

                if (count($kit->characteristicColorValue) > 0) {
                    foreach ($kit->characteristicColorValue as $val) {
                        $arrayCharacteristicColorValue['values'][$val->id] = [
                            'id' => $val->id,
                            'title' => $val->title,
                            'code' => $val->code,
                            'image' => $val->image,
                            'color' => $val->color,
                        ];
                    }
                }
                if (count($kit->characteristicValue) > 0) {
                    foreach ($kit->characteristicValue as $key => $value) {
                        $characteristic_value[$value['characteristic_id']]['tmp'][$value['id']] = [
                            'id' => $value->id,
                            'title' => $value->title,
                            'edit' => $value->edit,
                        ];
                    }
                    foreach ($characteristic_value as $value) {
                        if (isset($value['id'])) {
                            $arrayCharacteristicValue[$value['id']] = $value;
                            if (isset($arrayCharacteristicValue[$value['id']]['tmp'])) {
                                foreach ($arrayCharacteristicValue[$value['id']]['tmp'] as $v) {
                                    $arrayCharacteristicValue[$value['id']]['values'][] = $v;
                                }
                            }
                        }
                    }
                }
            }
            if (isset($arrayCharacteristicColorValue)) {
                foreach ($arrayCharacteristicColorValue['values'] as $value) {
                    $data['characteristic_color_value']['values'][] = $value;
                }
            }
            if (isset($arrayCharacteristicValue)) {
                foreach ($arrayCharacteristicValue as $value) {
                    $data['characteristic_value'][] = $value;
                }
            }
            $data['kits']['possible_balance_kits'] = (int)collect($data['kits']['body'])->min('count_kit');
            //$data[$key]['cells']['possible_balance_kits']= (int)collect($data[$key]['kits'])->min('count_kit');

        } elseif (isset($characteristic_value) && $item->kits->count() == 0) {
            foreach ($characteristic_value as $v) {
                $data['characteristic_value'][] = $v;
            }
        }
        return $data;
    }

    public function getServiceUpdate($item, $prices)
    {
        $data = [];

        $data['id'] = $item->id;
        $data['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);

        $data['is_visible'] = $item->is_visible;
        $data['is_actual'] = $item->is_actual;
        $data['created_at'] = $item->service->created_at;
        $data['updated_at'] = $item->service->updated_at;
        $data['service_id'] = $item->service->id;
        $data['short_title'] = $item->service->short_title;
        $data['dock_title'] = $item->service->dock_title;
        $data['desc'] = $item->service->desc;
        $data['sku'] = $item->service->sku;
        $data['unit_id'] = $item->service->unit_id;
        $data['organization_id'] = $item->service->organization_id;
        $data['supplier_id'] = $item->service->supplier_id;
        if (isset($item->service->units)) {
            $data['units'] = TitleJson::getArray($item->service->units->title, $this->lang);
        } else {
            $data['units'] = null;
        }

        if (isset($item->service->suppliers)) {
            $data['suppliers'] = $item->service->suppliers->title_supplier;
        } else {
            $data['suppliers'] = null;
        }

//        if (isset($item->service->organizations)) {
//            $data['organizations'] = $item->service->organizations->title;
//        } else {
//            $data['organizations'] = null;
//        }

        $data['prices'] = array();
        foreach ($item->prices->unique('id') as $price) {
            $data['prices'][] = [
                'is_buy' => $price->is_buy,
                'title' => TitleJson::getArray($price->title, $this->lang),
                'type_price_id' => $price->id,
                'value' => $price->pivot->value,
                'id' => $price->pivot->id,
                'is_default' => $price->is_default,
                'is_editable' => $price->is_editable,
                'is_rounding_without' => $price->is_rounding_without,
                'is_rounding_with' => $price->is_rounding_with,
                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                'symbol_currency' => TitleJson::getArray($price->symbol_currency ?? [], $this->lang),

            ];
        }

        $data['categories'] = $item->categories;
        $data['category'] = $item->category;

        return $data;
    }

    //not groups
    public function getSelectedNomenclaturesDocumentsTable($products)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        foreach ($products as $key => $item) {
            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                $data['arr'][$key]['cells']['packing'] = $item->product->packing ?? null;
                $data['arr'][$key]['cells']['balance'] = 0;

                if (isset($item->warehouses)) {
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->product->packing) && $item->product->packing > 0) {
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . Document::PACKING;
                    $data['arr'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->product->packing);
                    $data['arr'][$key]['balance_base'] = 0;
                } else {
                    $data['arr'][$key]['balance_base'] = 0;
                    $data['arr'][$key]['balance_stock_in_package'] = null;
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . TitleJson::getArray($item->product->units->title, $this->lang);
                }

                $data['arr'][$key]['balance_stock'] = $balance_stock;

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {

                        $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
                $data['arr'][$key]['type'] = Nomenclature::PRODUCT;

            } elseif (isset($item->kit)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->kit->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
                $data['arr'][$key]['cells']['packing'] = $item->kit->packing ?? null;
                $data['arr'][$key]['cells']['balance'] = 0;

                if (isset($item->warehouses)) {
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->kit->packing) && $item->kit->packing > 0) {
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . Document::PACKING;
                    $data['arr'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->kit->packing);
                    $data['arr'][$key]['balance_base'] = 0;
                } else {
                    $data['arr'][$key]['balance_base'] = 0;
                    $data['arr'][$key]['balance_stock_in_package'] = null;
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . TitleJson::getArray($item->kit->units->title, $this->lang);
                }

                $data['arr'][$key]['balance_stock'] = $balance_stock;

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {

                        $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
                $data['arr'][$key]['type'] = Nomenclature::KIT;

                if (isset($item->kits) && $item->kits->count() > 0) {
                    $data['arr'][$key]['kit_general_count'] = 0;
                    $data['arr'][$key]['kit_reserve'] = 0;
                    foreach ($item->kits as $k => $val) {
                        $data['arr'][$key]['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;

                        $data['arr'][$key]['kits'][$k]['id'] = $val->id;
                        $data['arr'][$key]['kits'][$k]['cells']['convert_id'] = $val->product->convert_id;
                        if (isset($val->product->short_title)) {
                            $data['arr'][$key]['kits'][$k]['cells']['short_title'] = $val->product->short_title;
                        } else {
                            $data['arr'][$key]['kits'][$k]['cells']['short_title'] = null;
                        }

                        if (isset($val->warehouses)) {
                            $data['arr'][$key]['kit_general_count'] = $data['arr'][$key]['kit_general_count'] + $val->warehouses->sum('pivot.balance');
                        }
                        if (isset($val->warehouses)) {
                            $data['arr'][$key]['kit_reserve'] = $data['arr'][$key]['kit_reserve'] + $val->warehouses->sum('pivot.reserve');
                        }

                        if (isset($val->warehouses)) {
                            $data['arr'][$key]['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve') . '/' . $val->warehouses->sum('pivot.balance');
                        }

                        //$data['arr'][$key]['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve') ?? null;
                        //$data['arr'][$key]['kits'][$k]['cells']['min_price'] = $val->product->min_price ?? null;

//                        $data['arr'][$key]['kits'][$k]['cells']['min_price_option'] = [
//                            'currency_title' => TitleJson::getArray($this->getDefaultCurrency(), $this->lang),
//                        ];

                        $data['arr'][$key]['kits'][$k]['cells']['packing'] = $val->product->packing ?? null;
                        if (isset($val->product->units->title)) {
                            $data['arr'][$key]['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                        } else {
                            $data['arr'][$key]['kits'][$k]['cells']['units'] = null;
                        }
                        //$data['arr'][$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                        if (isset($val->prices)) {
                            foreach ($val->prices as $price) {
                                $data['arr'][$key]['kits'][$k]['cells']['price'] = $price->pivot->value;
                                if (isset($price->pivot->percent)) {
                                    $data['arr'][$key]['kits'][$k]['cells']['percent'] = $price->pivot->percent;
                                    $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                                    $data['arr'][$key]['kits'][$k]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                                    if (isset($balance_base)) {
                                        $data['arr'][$key]['kits'][$k]['cells']['amount'] = $amount_percent * $balance_base;
                                    }
                                    if (isset($price->pivot->value)) {
                                        $data['sum_price'] = $data['sum_price'] + $amount_percent;
                                    }
                                } else {
                                    $data['arr'][$key]['kits'][$k]['cells']['percent'] = $price->pivot->percent ?? null;
                                    $data['arr'][$key]['kits'][$k]['cells']['amount_percent'] = 0;

                                    if (isset($balance_base)) {
                                        $data['arr'][$key]['kits'][$k]['cells']['amount'] = $price->pivot->value * $balance_base;
                                    }
                                    if (isset($price->pivot->value)) {
                                        $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                                    }
                                }
                            }
                        }
                    }
                    $data['arr'][$key]['cells']['possible_balance_kits'] = (int)collect($data['arr'][$key]['kits'])->min('count_kit');
                    $data['arr'][$key]['cells']['balance'] = $data['arr'][$key]['kit_general_count'] - $data['arr'][$key]['kit_reserve'] . '/' . $data['arr'][$key]['kit_general_count'];

                    $data['arr'][$key]['balance'] = $data['arr'][$key]['kit_general_count'] - $data['arr'][$key]['kit_reserve'] . '/' . $data['arr'][$key]['kit_general_count'];
                } else {
                    $data['arr'][$key]['kits'] = [];
                }
            }
        }
        return $data;
    }

    public function getSelectedServicesDocumentsTable($items)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        foreach ($items as $key => $item) {
            if (isset($item->service)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->service->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);
                $data['arr'][$key]['cells']['packing'] = $item->service->packing ?? null;
                $data['arr'][$key]['cells']['balance'] = 0;

                if (isset($item->warehouses)) {
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->service->packing) && $item->service->packing > 0) {
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . Document::PACKING;
                    $data['arr'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->service->packing);
                    $data['arr'][$key]['balance_base'] = 0;
                } else {
                    $data['arr'][$key]['balance_base'] = 0;
                    $data['arr'][$key]['balance_stock_in_package'] = null;
                    $data['arr'][$key]['cells']['balance_units'] = 0 . ' ' . TitleJson::getArray($item->service->units->title, $this->lang);
                }

                $data['arr'][$key]['balance_stock'] = $balance_stock;

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {

                        $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getProductsDocumentsTable($products, $price_id = null)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        foreach ($products as $key => $item) {
            if (isset($item->product)) {
                $data['arr']['product'][$key]['id'] = $item->id;
                $data['arr']['product'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr']['product'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr']['product'][$key]['cells']['sku'] = $item->product->sku ?? null;

                if (isset($item->product->packing) && $item->pivot->is_packing == Yesno::YES) {
                    $data['arr']['product'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['product'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                }
                $data['arr']['product'][$key]['cells']['packing'] = $item->product->packing ?? null;
                $data['arr']['product'][$key]['cells']['balance'] = optional($item)->pivot->balance ?? 0;

                if (count($item->warehouses) > 0) {
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->product->packing)) {
                    if ($item->pivot->is_packing == Yesno::YES) {
                        $balance = optional($item)->pivot->balance * intval($item->product->packing);
                        $units_title = TitleJson::getArray($item->product->units->title, $this->lang);
                        $balance_base = optional($item)->pivot->balance;
                        //$balance_base = $balance;
                    } else {
                        $balance = optional($item)->pivot->balance / intval($item->product->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($item)->pivot->balance;
                    }

                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->product->packing);
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                } else {
                    $balance_base = optional($item)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = null;
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                    $balance = optional($item)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($item->product->units->title, $this->lang);
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {
                        //if ($price->id == $price_id) {
                        //$data['arr'][$key]['cells']['amount_percent'] = $price->pivot->value/100 * $price->pivot->percent;
                        $data['arr']['product'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
//                        $data['arr'][$key]['cells']['percent'] = $price->pivot->percent;
//                        $amount_percent = $price->pivot->value * ( 100 +  $price->pivot->percent ) / 100;
//                        $data['arr'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;
//
//                        if (isset($balance_base)) {
//                            $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
//                        }
//                        if (isset($price->pivot->value)) {
//                            $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
//                        }
                        //}
                    }
                }
            } elseif ($item->service) {

                $data['arr']['service'][$key]['id'] = $item->id;
                $data['arr']['service'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr']['service'][$key]['cells']['short_title'] = $item->service->short_title;
                $data['arr']['service'][$key]['cells']['sku'] = $item->service->sku ?? null;

                if (isset($item->service->packing) && $item->pivot->is_packing == Yesno::YES) {
                    $data['arr']['service'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['service'][$key]['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);
                }
                $data['arr']['service'][$key]['cells']['packing'] = $item->service->packing ?? null;
                $data['arr']['service'][$key]['cells']['balance'] = optional($item)->pivot->balance ?? 0;

                if (count($item->warehouses) > 0) {
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->service->packing)) {
                    if ($item->pivot->is_packing == Yesno::YES) {
                        $balance = optional($item)->pivot->balance * intval($item->service->packing);
                        $units_title = TitleJson::getArray($item->service->units->title, $this->lang);
                        $balance_base = optional($item)->pivot->balance;
                        //$balance_base = $balance;
                    } else {
                        $balance = optional($item)->pivot->balance / intval($item->service->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($item)->pivot->balance;
                    }

                    $data['arr']['service'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['service'][$key]['balance_base'] = $balance_base;
                    $data['arr']['service'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->service->packing);
                    $data['arr']['service'][$key]['balance_stock'] = $balance_stock;

                } else {
                    $balance_base = optional($item)->pivot->balance ?? 0;
                    $data['arr']['service'][$key]['balance_base'] = $balance_base;
                    $data['arr']['service'][$key]['balance_stock_in_package'] = null;
                    $data['arr']['service'][$key]['balance_stock'] = $balance_stock;

                    $balance = optional($item)->pivot->balance ?? 0;
                    $data['arr']['service'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($item->service->units->title, $this->lang);
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {
                        //if ($price->id == $price_id) {
                        //$data['arr'][$key]['cells']['amount_percent'] = $price->pivot->value/100 * $price->pivot->percent;
                        $data['arr']['service'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr']['service'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr']['service'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr']['service'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr']['service'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr']['service'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr']['service'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getFillProductsStocks($products)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        foreach ($products as $key => $item) {

            if (isset($item->product)) {

                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                $data['arr'][$key]['cells']['packing'] = $item->product->packing ?? null;

                if (isset($item->warehouses)) {
                    $data['arr'][$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance');
                    $data['sum_balance'] = $data['sum_balance'] + $item->warehouses->sum('pivot.balance');
                    $balance_stock = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->product->packing) && $item->product->packing > 0) {
                    $data['arr'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->product->packing);
                } else {
                    $data['arr'][$key]['balance_stock_in_package'] = null;
                }
                $data['arr'][$key]['balance_stock'] = $balance_stock;

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {

                        $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $item->warehouses->sum('pivot.balance');
                        if (isset($price->pivot->value)) {
                            $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                        }
//                        } else {
//                            $data['arr'][$key]['cells']['price'] = null;
//                            $data['arr'][$key]['cells']['amount'] = null;
//                        }
                    }
                }
            }
        }
        return $data;
    }

    public function getProductDocumentsTable($item, $price_id = null)
    {
        $data = [];

        if (isset($item->product)) {
            $data['id'] = $item->id;
            $data['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
            $data['cells']['short_title'] = $item->product->short_title;
            //$data['cells']['sku'] = $item->product->sku ?? null;

            $data['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
            $data['cells']['packing'] = $item->product->packing ?? null;
            $data['cells']['balance'] = $item->pivot->balance ?? 1;
            $data['balance_base'] = $item->pivot->balance ?? 1;

            if (isset($item->prices) && isset($price_id)) {
                foreach ($item->prices as $price) {
                    if ($price->id == $price_id) {
                        $data['cells']['price'] = $price->pivot->value;
                    }
                }
            } else {
                $data['cells']['price'] = null;
            }
        }
        return $data;
    }

    public function getProductDocumentsStockTable($item)
    {
        $data = [];

        if (isset($item->product)) {
            $data['id'] = $item->id;
            $data['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
            $data['cells']['short_title'] = $item->product->short_title;
            $data['cells']['sku'] = $item->product->sku ?? null;

            $data['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
            $data['cells']['packing'] = $item->product->packing ?? null;

            $data['cells']['balance'] = $item->pivot->balance ?? 1;
            $data['balance_base'] = $item->pivot->balance ?? 1;

            if (count($item->warehouses) > 0) {
                $data['balance_stock'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
            } else {
                $data['balance_stock'] = 0;
            }

            if (isset($item->product->packing) && $item->product->packing > 0) {
                $data['balance_stock_in_package'] = $data['balance_stock'] / intval($item->product->packing);
            } else {
                $data['balance_stock_in_package'] = null;
            }
        }
        return $data;
    }

    public function getProductWarehouses($item)
    {
        $data = [];

        foreach ($item->warehouses as $key => $warehouse) {
            $data[$key]['warehouse'] = $warehouse->title;
            $data[$key]['unit'] = TitleJson::getArray($item->product->units->title, $this->lang);
            $data[$key]['remainder'] = $warehouse->pivot->balance - $warehouse->pivot->reserve;
            $data[$key]['reserve'] = $warehouse->pivot->reserve;
            $data[$key]['balance'] = $warehouse->pivot->balance;
        }

        return $data;
    }

    public function getCharacteristicsKits($products)
    {
        $data = [];
        foreach ($products as $key => $item) {
            if (count($item->characteristic) > 0) {
                foreach ($item->characteristic as $key => $value) {
                    if (count($value->characteristicColorValue) > 0) {
                        $data['characteristic_id'] = [
                            'id' => $value->id,
//                            'title' => $value->title,
//                            'is_base' => $value->pivot->is_base
                        ];
//                        $data['characteristic_color_value']['values'] = [];

//                    foreach ($value->characteristicColorValue as $val) {
//                        $data['characteristic_color_value']['values'][] = [
//                            'id' => $val->id,
//                            'title' => $val->title,
//                            'code' => $val->code,
//                            'image' => $val->image,
//                            'color' => $val->color,
//                        ];
//                    }
                    } else {
                        $characteristic_value[$value->id] = [
                            'id' => $value->id,
//                            'title' => $value->title,
//                            'is_base' => $value->pivot->is_base
                        ];
//                        $characteristic_value[$value->id]['values'] = [];
//                    foreach ($value->characteristicValue as $val) {
//
//                        $characteristic_value[$value->id]['values'][] = [
//                            'id' => $val->id,
//                            'title' => $val->title,
//                        ];
//                    }
                        if (isset($characteristic_value)) {
                            foreach ($characteristic_value as $val) {
                                $data['characteristic_id'][] = $val;
                            }
                        }
                    }
                }
            } else {
                foreach ($item->characteristicValue as $key => $value) {

                    $characteristic_value[$value['characteristic_id']] = [
                        'id' => $value->characteristic->id,
//                        'title' => $value->characteristic->title,
//                        'is_base' => $value->pivot->is_base
                    ];

//                    $characteristic_value[$value['characteristic_id']]['values'][] = [
//                        'id' => $value->id,
//                        'title' => $value->title,
//                        'edit' => $value->edit,
//                    ];
                }

                foreach ($characteristic_value as $value) {
                    $data['characteristic_id'][] = $value;
                }

                foreach ($item->characteristicColorValue as $value) {
                    $data['characteristic_id'][] = [
                        'id' => $value->characteristic->id,
                    ];
//                    $data['characteristic_color_value'] = [
//                        'id' => $value->characteristic->id,
//                        'title' => $value->characteristic->title,
//                        'is_base' => $value->pivot->is_base
//                    ];
//                    if ($item->is_groups == false) {
//                        $data['characteristic_color_value']['values'] = [
//                            'id' => $value->id,
//                            'title' => $value->title,
//                            'code' => $value->code,
//                            'image' => $value->image,
//                            'color' => $value->color,
//                        ];
//                    } else {
//                        $data['characteristic_color_value']['values'][] = [
//                            'id' => $value->id,
//                            'title' => $value->title,
//                            'code' => $value->code,
//                            'image' => $value->image,
//                            'color' => $value->color,
//                        ];
//                    }
                }
            }
        }

        $results = collect($data['characteristic_id']);
        $results = $results->unique('id');
        foreach ($results as $val) {
            $arr[] = $val;
        }
        //       $result = $result->toArray();
        return $arr;
    }

    public function getSelectedKitsTable($products)
    {
        $data = [];
        $data['sum_price'] = 0;
        foreach ($products as $key => $item) {
            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['sku'] = $item->product->sku ?? null;
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                if (isset($item->prices)) {
                    foreach ($item->prices->unique('id') as $price) {
                        $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->value)) {
                            $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                        }
                    }
                }
                if (count($item->warehouses) > 0) {
                    $data['arr'][$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
                    $data['arr'][$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                } else {
                    $data['arr'][$key]['cells']['reserve'] = null;
                    $data['arr'][$key]['cells']['balance'] = null;
                }
                $data['arr'][$key]['cells']['count'] = 1;
            }
        }
        return $data;
    }

    public function getSelectProductsTable($items)
    {
        $data = [];
        foreach ($items as $key => $item) {

            $data[$key]['id'] = $item->id;
            //$data[$key]['cells']['id'] = $item->id;
            if (isset($item->product->convert_id)) {
                $data[$key]['cells']['convert_id'] = $item->product->convert_id;
            } else {
                $data[$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
            }

            if (isset($item->product)) {
                $data[$key]['cells']['short_title'] = $item->product->short_title;

                if (isset($item->product->units)) {
                    $data[$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                } else {
                    $data[$key]['cells']['units'] = null;
                }

                if (count($item->warehouses) > 0) {
                    $data[$key]['cells']['remainder'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                    $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                    $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance');
                } else {
                    $data[$key]['cells']['remainder'] = null;
                    $data[$key]['cells']['reserve'] = null;
                    $data[$key]['cells']['balance'] = null;
                }

                $data[$key]['is_groups'] = $item->product->is_groups;

                if (isset($item->groups) && $item->groups->count() > 0) {

                    $data[$key]['group_general_count'] = 0;
                    $data[$key]['group_reserve'] = 0;
                    foreach ($item->groups as $k => $group) {

                        $data[$key]['groups'][$k]['id'] = $group->id;
                        $data[$key]['groups'][$k]['cells']['convert_id'] = str_pad($group->id, 8, '0', STR_PAD_LEFT);
                        if (isset($group->product->short_title)) {
                            $data[$key]['groups'][$k]['cells']['short_title'] = $group->product->short_title;
                        } else {
                            $data[$key]['groups'][$k]['cells']['short_title'] = null;
                        }

                        if (isset($group->product->units->title)) {
                            $data[$key]['groups'][$k]['cells']['units'] = TitleJson::getArray($group->product->units->title, $this->lang);
                        }

                        if (count($group->warehouses) > 0) {
                            $data[$key]['groups'][$k]['cells']['remainder'] = $group->warehouses->sum('pivot.balance') - $group->warehouses->sum('pivot.reserve');
                            $data[$key]['groups'][$k]['cells']['reserve'] = $group->warehouses->sum('pivot.reserve');
                            $data[$key]['groups'][$k]['cells']['balance'] = $group->warehouses->sum('pivot.balance');
                        } else {
                            $data[$key]['groups'][$k]['cells']['remainder'] = null;
                            $data[$key]['groups'][$k]['cells']['reserve'] = null;
                            $data[$key]['groups'][$k]['cells']['balance'] = null;
                        }

                        $data[$key]['group_general_count'] = $data[$key]['group_general_count'] + $group->warehouses->sum('pivot.balance');
                        $data[$key]['group_reserve'] = $data[$key]['group_reserve'] + $group->warehouses->sum('pivot.reserve');
                    }
                    $data[$key]['cells']['remainder'] = $data[$key]['group_general_count'] - $data[$key]['group_reserve'];
                    $data[$key]['cells']['reserve'] = $data[$key]['group_reserve'];
                    $data[$key]['cells']['balance'] = $data[$key]['group_general_count'];
                }
            } elseif (isset($item->kit)) {
                $data[$key]['cells']['short_title'] = $item->kit->short_title;

                if (isset($item->kit->units)) {
                    $data[$key]['cells']['units'] = TitleJson::getArray($item->kit->units->title, $this->lang);
                } else {
                    $data[$key]['cells']['units'] = null;
                }

                if (count($item->warehouses) > 0) {
                    $data[$key]['cells']['remainder'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve');
                    $data[$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                    $data[$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance');
                } else {
                    $data[$key]['cells']['remainder'] = null;
                    $data[$key]['cells']['reserve'] = null;
                    $data[$key]['cells']['balance'] = null;
                }

                // $data[$key]['is_groups'] = $item->product->is_groups;
                // dd($item->kits);
                if (isset($item->kits) && $item->kits->count() > 0) {

                    $data[$key]['kit_general_count'] = 0;
                    $data[$key]['kit_reserve'] = 0;
                    foreach ($item->kits as $k => $val) {
                        $data[$key]['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;
                        $data[$key]['kits'][$k]['id'] = $val->id;
                        $data[$key]['kits'][$k]['cells']['convert_id'] = str_pad($val->id, 8, '0', STR_PAD_LEFT);
                        if (isset($val->product->short_title)) {
                            $data[$key]['kits'][$k]['cells']['short_title'] = $val->product->short_title;
                        } else {
                            $data[$key]['kits'][$k]['cells']['short_title'] = null;
                        }

                        if (isset($val->product->units->title)) {
                            $data[$key]['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                        }

                        if (count($val->warehouses) > 0) {
                            $data[$key]['kits'][$k]['cells']['remainder'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve');
                            $data[$key]['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve');
                            $data[$key]['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance');
                        } else {
                            $data[$key]['kits'][$k]['cells']['remainder'] = null;
                            $data[$key]['kits'][$k]['cells']['reserve'] = null;
                            $data[$key]['kits'][$k]['cells']['balance'] = null;
                        }

                        $data[$key]['kit_general_count'] = $data[$key]['kit_general_count'] + $val->warehouses->sum('pivot.balance');
                        $data[$key]['kit_reserve'] = $data[$key]['kit_reserve'] + $val->warehouses->sum('pivot.reserve');
                        $data[$key]['cells']['possible_balance_kits'] = (int)collect($data[$key]['kits'])->min('count_kit');
                    }

                    $data[$key]['cells']['remainder'] = $data[$key]['kit_general_count'] - $data[$key]['kit_reserve'];
                    $data[$key]['cells']['reserve'] = $data[$key]['kit_reserve'];
                    $data[$key]['cells']['balance'] = $data[$key]['kit_general_count'];
                }
            }
        }
        return $data;
    }

    public function getSelectServicesTable($items)
    {
        $data = [];
        foreach ($items as $key => $item) {

            $data[$key]['id'] = $item->id;
            $data[$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);

            if (isset($item->service)) {
                $data[$key]['cells']['short_title'] = $item->service->short_title;

                if (isset($item->service->units)) {
                    $data[$key]['cells']['units'] = TitleJson::getArray($item->service->units->title, $this->lang);
                } else {
                    $data[$key]['cells']['units'] = null;
                }
            }
        }
        return $data;
    }

    public function getRelatedProductsTable($products)
    {
        $data = [];
        foreach ($products as $key => $item) {

            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = $item->product->convert_id;
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
                $data['arr'][$key]['cells']['sku'] = $item->product->sku ?? null;

                if (isset($item->warehouses)) {
                    $data['arr'][$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance');
                }

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {
                        if ($price->is_default == Yesno::YES) {
                            $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        }

                    }
                }
            }
        }
        return $data;
    }

    public function getProductsInRelatedOrAnalogTable($products)
    {
        $data = [];
        foreach ($products as $key => $item) {

            if (isset($item->product)) {

                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['sku'] = $item->product->sku;
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);

                if (isset($item->prices)) {
                    foreach ($item->prices as $price) {
                        if ($price->is_default == Yesno::YES) {
                            $data['arr'][$key]['cells']['price'] = $price->pivot->value;
                        }
                    }
                }
            }
        }
        return $data;
    }

    public function tableRelatedOrAnalogProducts($items, $prices)
    {
        $data = [];
        foreach ($items as $key => $item) {
            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['photo'] = $item->files->firstWhere('is_image', Yesno::YES)->full_path ?? null;

                $data['arr'][$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                $data['arr'][$key]['cells']['sku'] = $item->product->sku;

                if (isset($item->warehouses)) {
                    $data['arr'][$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance');
                }

                for ($i = 1; $i <= $prices->count(); $i++) {
                    $data['arr'][$key]['cells']['price_' . $i] = null;

                    $data['arr'][$key]['cells']['price_' . $i . '_option'] = [
                        'id' => null,
                        'is_default' => null,
                        'is_rounding_without' => null,
                        'is_rounding_with' => null,
                        'currency_title' => null,
                    ];
                }
                foreach ($item->prices->unique('id') as $price) {
                    $data['arr'][$key]['cells']['price_' . $price->id] = $price->pivot->value;

                    $data['arr'][$key]['cells']['price_' . $price->id . '_option'] = [
                        'id' => $price->pivot->id,
                        'is_default' => $price->is_default,
                        'is_rounding_without' => $price->is_rounding_without,
                        'is_rounding_with' => $price->is_rounding_with,
                        'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                    ];
                }
            }
        }
        return $data;
    }

    public function tableRelatedNomenclaturesInNomenclature($items, $prices)
    {
        $data = [];
        foreach ($items as $key => $item) {
            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
                if ($item->files) {
                    $data['arr'][$key]['cells']['photo'] = $item->files->firstWhere('is_image', Yesno::YES)->full_path ?? null;
                }

                $data['arr'][$key]['cells']['convert_id'] = $item->product->convert_id;
                $data['arr'][$key]['cells']['sku'] = $item->product->sku;
                $data['arr'][$key]['cells']['short_title'] = $item->product->short_title;
                if (isset($item->warehouses)) {
                    $data['arr'][$key]['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');
                }
                //$data['arr'][$key]['cells']['reserve'] = $item->warehouses->sum('pivot.reserve');
                $data['arr'][$key]['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);

                for ($i = 1; $i <= $prices->count(); $i++) {
                    $data['arr'][$key]['cells']['price_' . $i] = null;

                    $data['arr'][$key]['cells']['price_' . $i . '_option'] = [
                        'id' => null,
                        'is_default' => null,
                        'is_rounding_without' => null,
                        'is_rounding_with' => null,
                        'currency_title' => null,
                    ];
                }

                foreach ($item->prices->unique('id') as $price) {
                    $data['arr'][$key]['cells']['price_' . $price->id] = $price->pivot->value;

                    $data['arr'][$key]['cells']['price_' . $price->id . '_option'] = [
                        'id' => $price->pivot->id,
                        'is_default' => $price->is_default,
                        'is_rounding_without' => $price->is_rounding_without,
                        'is_rounding_with' => $price->is_rounding_with,
                        'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                    ];
                }

                $data['arr'][$key]['cells']['photo'] = $item->files->firstWhere('is_image', Yesno::YES)->full_path ?? null;
            }
        }
        return $data;
    }

    public function getItemIds($items)
    {
        $data = [];
        foreach ($items as $key => $item) {
            if (isset($item->product)) {
                $data['arr'][$key]['id'] = $item->id;
            }
        }
        return $data;
    }

    public function tableRelatedProduct($item, $prices)
    {
        $data = [];

        if (isset($item->product)) {
            $data['id'] = $item->id;
            $data['cells']['photo'] = $item->files->firstWhere('is_image', Yesno::YES)->full_path ?? null;
            $data['cells']['convert_id'] = $item->product->convert_id;
            $data['cells']['short_title'] = $item->product->short_title;
            $data['cells']['sku'] = $item->product->sku;
            if (isset($item->product->units)) {
                $data['cells']['units'] = TitleJson::getArray($item->product->units->title, $this->lang);
            } else {
                $data['cells']['units'] = null;
            }
            $data['cells']['balance'] = $item->warehouses->sum('pivot.balance') - $item->warehouses->sum('pivot.reserve') . '/' . $item->warehouses->sum('pivot.balance');

            for ($i = 1; $i <= $prices->count(); $i++) {
                $data['cells']['price_' . $i] = null;
                $data['cells']['price_' . $i . '_option'] = [
                    'id' => null,
                    'is_default' => null,
                    'is_rounding_without' => null,
                    'is_rounding_with' => null,
                    'currency_title' => null,
                ];
            }
            foreach ($item->prices->unique('id') as $price) {
                $data['cells']['price_' . $price->id] = $price->pivot->value;

                $data['cells']['price_' . $price->id . '_option'] = [
                    'id' => $price->pivot->id,
                    'is_default' => $price->is_default,
                    'is_rounding_without' => $price->is_rounding_without,
                    'is_rounding_with' => $price->is_rounding_with,
                    'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
                ];
            }
        }
        return $data;
    }

    public function getNomenclaturePricesTable($items)
    {
        $data = [];
        foreach ($items as $key => $item) {

            if (isset($item)) {
                $data['arr'][$key]['id'] = $item->id;
                $data['arr'][$key]['cells']['date'] = $item->date;

                if (isset($item->price)) {
                    $data['arr'][$key]['cells']['title_price'] = TitleJson::getArray($item->price->title, $this->lang);
                    $data['arr'][$key]['cells']['currency'] = TitleJson::getArray($item->price->currency_id, $this->lang);
                    $data['arr'][$key]['cells']['type_price'] = TitleJson::getArray($item->price->type_price_id, $this->lang);
                } else {
                    $data['arr'][$key]['cells']['title_price'] = null;
                    $data['arr'][$key]['cells']['currency'] = null;
                    $data['arr'][$key]['cells']['type_price'] = null;
                }

                if (isset($item->price->is_buy)) {
                    $data['arr'][$key]['cells']['is_buy'] = $item->price->is_buy;
                } else {
                    $data['arr'][$key]['cells']['is_buy'] = null;
                }

                $data['arr'][$key]['cells']['supplier'] = $item->suppliers->title_supplier ?? null;
                if (isset($item->nomenclature->product)) {
                    $data['arr'][$key]['cells']['nomenclature'] = $item->nomenclature->product->short_title;
                } elseif (isset($item->nomenclature->kit)) {
                    $data['arr'][$key]['cells']['nomenclature'] = $item->nomenclature->kit->short_title;
                } elseif (isset($item->nomenclature->service)) {
                    $data['arr'][$key]['cells']['nomenclature'] = $item->nomenclature->service->short_title;
                } else {
                    $data['arr'][$key]['cells']['nomenclature'] = null;
                }

                //$data['arr'][$key]['cells']['nomenclature'] = $item->nomenclature->product->short_title ?? $item->nomenclature->kit->short_title;
                $data['arr'][$key]['cells']['price'] = $item->value ?? null;

                if (isset($item->units)) {
                    $data['arr'][$key]['cells']['unit'] = TitleJson::getArray($item->units->title, $this->lang);
                } else {
                    $data['arr'][$key]['cells']['unit'] = null;
                }
            }
        }
        return $data;
    }

    public function getPricesTable($items)
    {
        $data = [];
        foreach ($items->prices as $key => $price) {

            $data[$key]['id'] = $price->pivot->id;
            $data[$key]['type_price_id'] = $price->id;
            $data[$key]['cells']['date'] = $price->pivot->date;
            $data[$key]['cells']['title_price'] = TitleJson::getArray($price->title, $this->lang);
            $data[$key]['cells']['currency'] = TitleJson::getArray($price->currency_id, $this->lang);
            $data[$key]['cells']['type_price'] = TitleJson::getArray($price->type_price_id, $this->lang);

            if (isset($price->is_buy)) {
                $data[$key]['cells']['is_buy'] = $price->is_buy;
            } else {
                $data[$key]['cells']['is_buy'] = null;
            }

            $data[$key]['cells']['supplier'] = $items->product->suppliers->title_supplier ?? null;
            $data[$key]['cells']['nomenclature'] = $items->product->short_title ?? null;
            $data[$key]['cells']['price'] = $price->pivot->value ?? null;

            if (isset($items->product->units)) {
                $data[$key]['cells']['unit'] = TitleJson::getArray($items->product->units->title, $this->lang);
            } else {
                $data[$key]['cells']['unit'] = null;
            }

//            $data['cells']['price_' . $price->id] = $price->pivot->value;
//
//            $data['cells']['price_' . $price->id . '_option'] = [
//                'id' => $price->pivot->id,
//                'is_default' => $price->is_default,
//                'is_rounding_without' => $price->is_rounding_without,
//                'is_rounding_with' => $price->is_rounding_with,
//                'currency_title' => TitleJson::getArray($price->currency_id, $this->lang),
//            ];
        }
        return $data;
    }


    public function getOpenBasketTable($items)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        //dd($items);
        foreach ($items as $key => $item) {
            if (isset($item->nomenclatures->product)) {
                $data['arr']['product'][$key]['id'] = $item->nomenclatures->id;
                $data['arr']['product'][$key]['cells']['convert_id'] = $item->nomenclatures->product->convert_id;
                $data['arr']['product'][$key]['cells']['short_title'] = $item->nomenclatures->product->short_title;
                $data['arr']['product'][$key]['cells']['sku'] = $item->nomenclatures->product->sku ?? null;

                if (isset($item->nomenclatures->product->packing) && $item->nomenclatures->pivot->is_packing == Yesno::YES) {
                    $data['arr']['product'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['product'][$key]['cells']['units'] = TitleJson::getArray($item->nomenclatures->product->units->title, $this->lang);
                }
                $data['arr']['product'][$key]['cells']['packing'] = $item->nomenclatures->product->packing ?? null;
                $data['arr']['product'][$key]['cells']['balance'] = optional($item->nomenclatures)->pivot->balance ?? 0;

                //dd($item->nomenclatures);
                if (count($item->nomenclatures->warehouses) > 0) {
                    $balance_stock = $item->nomenclatures->warehouses->sum('pivot.balance') - $item->nomenclatures->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }
                if (isset($item->nomenclatures->product->packing)) {

                    if ($item->nomenclatures->pivot->is_packing == Yesno::YES) {
                        $balance = optional($item->nomenclatures)->pivot->balance * intval($item->nomenclatures->product->packing);
                        $units_title = TitleJson::getArray($item->nomenclatures->product->units->title, $this->lang);
                        $balance_base = optional($item->nomenclatures)->pivot->balance;
                        //$balance_base = $balance;
                    } else {
                        $balance = optional($item->nomenclatures)->pivot->balance / intval($item->nomenclatures->product->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($item->nomenclatures)->pivot->balance;
                    }

                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->nomenclatures->product->packing);
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                } else {
                    $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = null;
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                    $balance = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($item->nomenclatures->product->units->title, $this->lang);
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->nomenclatures->prices)) {
                    foreach ($item->nomenclatures->prices as $price) {
                        //if ($price->id == $price_id) {
                        //$data['arr'][$key]['cells']['amount_percent'] = $price->pivot->value/100 * $price->pivot->percent;
                        $data['arr']['product'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
//                        $data['arr'][$key]['cells']['percent'] = $price->pivot->percent;
//                        $amount_percent = $price->pivot->value * ( 100 +  $price->pivot->percent ) / 100;
//                        $data['arr'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;
//
//                        if (isset($balance_base)) {
//                            $data['arr'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
//                        }
//                        if (isset($price->pivot->value)) {
//                            $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
//                        }
                        //}
                    }
                }
            } elseif (isset($item->nomenclatures->kit)) {
                $data['arr']['product'][$key]['id'] = $item->nomenclatures->id;
                $data['arr']['product'][$key]['cells']['convert_id'] = $item->nomenclatures->kit->convert_id; //str_pad($item->nomenclatures->id, 8, '0', STR_PAD_LEFT);
                $data['arr']['product'][$key]['cells']['short_title'] = $item->nomenclatures->kit->short_title;
                $data['arr']['product'][$key]['cells']['sku'] = $item->nomenclatures->kit->sku ?? null;
                $data['arr']['product'][$key]['cells']['units'] = TitleJson::getArray($item->nomenclatures->kit->units->title, $this->lang);
                $data['arr']['product'][$key]['cells']['packing'] = $item->nomenclatures->kit->packing ?? null;
                $data['arr']['product'][$key]['cells']['balance'] = optional($item->nomenclatures)->pivot->balance ?? 0;

                if (count($item->nomenclatures->warehouses) > 0) {
                    $balance_stock = $item->nomenclatures->warehouses->sum('pivot.balance') - $item->nomenclatures->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->nomenclatures->kit->packing)) {
                    if (isset($item->nomenclatures->pivot) && $item->nomenclatures->pivot->is_packing == Yesno::YES) {
                        $balance = optional($item->nomenclatures)->pivot->balance * intval($item->nomenclatures->kit->packing);
                        $units_title = TitleJson::getArray($item->nomenclatures->kit->units->title, $this->lang);
                        $balance_base = optional($item->nomenclatures)->pivot->balance;
                        //$balance_base = $balance;
                    } else {
                        $balance = (optional($item->nomenclatures)->pivot->balance ?? 0) / intval($item->nomenclatures->kit->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    }

                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->nomenclatures->kit->packing);
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                } else {
                    $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $data['arr']['product'][$key]['balance_stock_in_package'] = null;
                    $data['arr']['product'][$key]['balance_stock'] = $balance_stock;

                    $balance = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($item->nomenclatures->kit->units->title, $this->lang);
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->nomenclatures->prices)) {
                    foreach ($item->nomenclatures->prices as $price) {
                        //if ($price->id == $price_id) {
                        //$data['arr'][$key]['cells']['amount_percent'] = $price->pivot->value/100 * $price->pivot->percent;
                        $data['arr']['product'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr']['product'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr']['product'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr']['product'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
                $data['arr']['product'][$key]['type'] = Nomenclature::KIT;

                if (isset($item->nomenclatures->kits) && $item->nomenclatures->kits->count() > 0) {
                    $data['arr']['product'][$key]['kit_general_count'] = 0;
                    $data['arr']['product'][$key]['kit_reserve'] = 0;
                    foreach ($item->nomenclatures->kits as $k => $val) {
                        $data['arr']['product'][$key]['kits'][$k]['count_kit'] = ($val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve')) / $val->pivot->count;

                        $data['arr']['product'][$key]['kits'][$k]['id'] = $val->id;
                        $data['arr']['product'][$key]['kits'][$k]['cells']['convert_id'] = $val->product->convert_id;
                        if (isset($val->product->short_title)) {
                            $data['arr']['product'][$key]['kits'][$k]['cells']['short_title'] = $val->product->short_title;
                        } else {
                            $data['arr']['product'][$key]['kits'][$k]['cells']['short_title'] = null;
                        }
                        $data['arr']['product'][$key]['kits'][$k]['cells']['sku'] = $item->product->sku ?? null;

                        if (isset($val->warehouses)) {
                            $data['arr']['product'][$key]['kit_general_count'] = $data['arr']['product'][$key]['kit_general_count'] + $val->warehouses->sum('pivot.balance');
                        }
                        if (isset($val->warehouses)) {
                            $data['arr']['product'][$key]['kit_reserve'] = $data['arr']['product'][$key]['kit_reserve'] + $val->warehouses->sum('pivot.reserve');
                        }

                        if (count($val->warehouses) > 0) {
                            $balance_stock = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve');
                        } else {
                            $balance_stock = 0;
                        }
                        if (isset($val->product->packing)) {

                            if ($val->pivot->is_packing == Yesno::YES) {
                                $balance = optional($val)->pivot->balance * intval($val->product->packing);
                                $units_title = TitleJson::getArray($val->product->units->title, $this->lang);
                                $balance_base = optional($val)->pivot->balance ?? 0;
                                //$balance_base = $balance;
                            } else {
                                $balance = optional($val)->pivot->balance / intval($val->product->packing);
                                $units_title = Document::PACKING;
                                $balance_base = optional($val)->pivot->balance ?? 0;
                            }
                            $data['arr']['product'][$key]['kits'][$k]['cells']['balance_units'] = $balance . ' ' . $units_title;
                            $data['arr']['product'][$key]['kits'][$k]['balance_base'] = $balance_base;
                            $data['arr']['product'][$key]['kits'][$k]['balance_stock_in_package'] = $balance_stock / intval($val->product->packing);
                            $data['arr']['product'][$key]['kits'][$k]['balance_stock'] = $balance_stock;
                        } else {
                            $balance_base = optional($val)->pivot->balance ?? 0;
                            $data['arr']['product'][$key]['kits'][$k]['balance_base'] = $balance_base;
                            $data['arr']['product'][$key]['kits'][$k]['balance_stock_in_package'] = null;
                            $data['arr']['product'][$key]['kits'][$k]['balance_stock'] = $balance_stock;

                            $balance = optional($val)->pivot->balance ?? 0;
                            $data['arr']['product'][$key]['kits'][$k]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($val->product->units->title, $this->lang);
                        }

                        if (isset($balance_base)) {
                            $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                        }
//                        if (isset($val->warehouses)) {
//                            $data['arr']['product'][$key]['kits'][$k]['cells']['balance'] = $val->warehouses->sum('pivot.balance') - $val->warehouses->sum('pivot.reserve') . '/' . $val->warehouses->sum('pivot.balance');
//                        }

                        //$data['arr']['product'][$key]['kits'][$k]['cells']['reserve'] = $val->warehouses->sum('pivot.reserve') ?? null;
                        //$data['arr']['product'][$key]['kits'][$k]['cells']['min_price'] = $val->product->min_price ?? null;

//                        $data['arr']['product'][$key]['kits'][$k]['cells']['min_price_option'] = [
//                            'currency_title' => TitleJson::getArray($this->getDefaultCurrency(), $this->lang),
//                        ];

                        $data['arr']['product'][$key]['kits'][$k]['cells']['packing'] = $val->product->packing ?? null;
                        if (isset($val->product->units->title)) {
                            $data['arr']['product'][$key]['kits'][$k]['cells']['units'] = TitleJson::getArray($val->product->units->title, $this->lang);
                        } else {
                            $data['arr']['product'][$key]['kits'][$k]['cells']['units'] = null;
                        }
                        //$data['arr']['product'][$key]['cells']['countries'] = TitleJson::get($value->countries->title, $this->lang);
                        if (isset($val->prices)) {
                            foreach ($val->prices as $price) {
                                $data['arr']['product'][$key]['kits'][$k]['cells']['price'] = $price->pivot->value;
                                if (isset($price->pivot->percent)) {
                                    $data['arr']['product'][$key]['kits'][$k]['cells']['percent'] = $price->pivot->percent;
                                    $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                                    $data['arr']['product'][$key]['kits'][$k]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                                    if (isset($balance_base)) {
                                        $data['arr']['product'][$key]['kits'][$k]['cells']['amount'] = $amount_percent * $balance_base;
                                    }
                                    if (isset($price->pivot->value)) {
                                        $data['sum_price'] = $data['sum_price'] + $amount_percent;
                                    }
                                } else {
                                    $data['arr']['product'][$key]['kits'][$k]['cells']['percent'] = $price->pivot->percent ?? null;
                                    $data['arr']['product'][$key]['kits'][$k]['cells']['amount_percent'] = 0;

                                    if (isset($balance_base)) {
                                        $data['arr']['product'][$key]['kits'][$k]['cells']['amount'] = $price->pivot->value * $balance_base;
                                    }
//                                    if (isset($price->pivot->value)) {
//                                        $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
//                                    }
                                }
                            }
                        }
                    }
                    $data['arr']['product'][$key]['cells']['balance'] = $data['arr']['product'][$key]['kit_general_count'] - $data['arr']['product'][$key]['kit_reserve'] . '/' . $data['arr']['product'][$key]['kit_general_count'];

                    $data['arr']['product'][$key]['balance'] = $data['arr']['product'][$key]['kit_general_count'] - $data['arr']['product'][$key]['kit_reserve'] . '/' . $data['arr']['product'][$key]['kit_general_count'];
                    $data['arr']['product'][$key]['cells']['possible_balance_kits'] = (int)collect($data['arr']['product'][$key]['kits'])->min('count_kit');


                } else {
                    $data['arr']['product'][$key]['kits'] = [];
                }
            } elseif ($item->nomenclatures->service) {
                $data['arr']['service'][$key]['id'] = $item->nomenclatures->id;
                $data['arr']['service'][$key]['cells']['convert_id'] = str_pad($item->nomenclatures->id, 8, '0', STR_PAD_LEFT);
                $data['arr']['service'][$key]['cells']['short_title'] = $item->nomenclatures->service->short_title;
                $data['arr']['service'][$key]['cells']['sku'] = $item->nomenclatures->service->sku ?? null;

                if (isset($item->nomenclatures->service->packing) && $item->nomenclatures->pivot->is_packing == Yesno::YES) {
                    $data['arr']['service'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['service'][$key]['cells']['units'] = TitleJson::getArray($item->nomenclatures->service->units->title, $this->lang);
                }
                $data['arr']['service'][$key]['cells']['packing'] = $item->nomenclatures->service->packing ?? null;
                $data['arr']['service'][$key]['cells']['balance'] = optional($item->nomenclatures)->pivot->balance ?? 0;

                if (count($item->nomenclatures->warehouses) > 0) {
                    $balance_stock = $item->nomenclatures->warehouses->sum('pivot.balance') - $item->nomenclatures->warehouses->sum('pivot.reserve');
                } else {
                    $balance_stock = 0;
                }

                if (isset($item->nomenclatures->service->packing)) {
                    if ($item->nomenclatures->pivot->is_packing == Yesno::YES) {
                        $balance = optional($item->nomenclatures)->pivot->balance * intval($item->nomenclatures->service->packing);
                        $units_title = TitleJson::getArray($item->nomenclatures->service->units->title, $this->lang);
                        $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    } else {
                        $balance = optional($item->nomenclatures)->pivot->balance / intval($item->nomenclatures->service->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    }

                    $data['arr']['service'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['service'][$key]['balance_base'] = $balance_base;
                    $data['arr']['service'][$key]['balance_stock_in_package'] = $balance_stock / intval($item->nomenclatures->service->packing);
                    $data['arr']['service'][$key]['balance_stock'] = $balance_stock;

                } else {
                    $balance_base = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['service'][$key]['balance_base'] = $balance_base;
                    $data['arr']['service'][$key]['balance_stock_in_package'] = null;
                    $data['arr']['service'][$key]['balance_stock'] = $balance_stock;

                    $balance = optional($item->nomenclatures)->pivot->balance ?? 0;
                    $data['arr']['service'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($item->nomenclatures->service->units->title, $this->lang);
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                }

                if (isset($item->nomenclatures->prices)) {
                    foreach ($item->nomenclatures->prices as $price) {
                        //if ($price->id == $price_id) {
                        //$data['arr'][$key]['cells']['amount_percent'] = $price->pivot->value/100 * $price->pivot->percent;
                        $data['arr']['service'][$key]['cells']['price'] = $price->pivot->value;
                        if (isset($price->pivot->percent)) {
                            $data['arr']['service'][$key]['cells']['percent'] = $price->pivot->percent;
                            $amount_percent = $price->pivot->value * (100 + $price->pivot->percent) / 100;
                            $data['arr']['service'][$key]['cells']['amount_percent'] = $amount_percent - $price->pivot->value;

                            if (isset($balance_base)) {
                                $data['arr']['service'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $amount_percent;
                            }
                        } else {
                            $data['arr']['service'][$key]['cells']['percent'] = $price->pivot->percent ?? null;
                            $data['arr']['service'][$key]['cells']['amount_percent'] = 0;

                            if (isset($balance_base)) {
                                $data['arr']['service'][$key]['cells']['amount'] = $price->pivot->value * $balance_base;
                            }
                            if (isset($price->pivot->value)) {
                                $data['sum_price'] = $data['sum_price'] + $price->pivot->value;
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getListTable($items)
    {
        $data = [];
        $data['sum_price'] = 0;
        $data['sum_balance'] = 0;
        $data['sum_product_balance'] = 0;
        $data['sum_service_balance'] = 0;
        $data['sum_product_price'] = 0;
        $data['sum_service_price'] = 0;
        $data['sum_discount_price'] = 0;
        foreach ($items->nomenclatures as $key => $value) {
            if (isset($value->product)) {
                $data['arr']['product'][$key]['id'] = $value->product->id;
                $data['arr']['product'][$key]['min_price'] = $value->product->min_price;
                $data['arr']['product'][$key]['cells']['convert_id'] = $value->product->convert_id;
                $data['arr']['product'][$key]['cells']['sku'] = $value->product->sku;
                $data['arr']['product'][$key]['cells']['title'] = $value->product->short_title;
                if (isset($value->product->packing) && $value->pivot->is_packing == Yesno::YES) {
                    $data['arr']['product'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['product'][$key]['cells']['units'] = TitleJson::getArray($value->product->units->title, $this->lang);
                }
                $data['arr']['product'][$key]['cells']['packing'] = $value->product->packing ?? null;

                $data['arr']['product'][$key]['cells']['balance'] = $value->pivot->balance;

                if (isset($value->product->packing)) {
                    if ($value->pivot->is_packing == Yesno::YES) {
                        $balance = optional($value)->pivot->balance * intval($value->product->packing);
                        $units_title = TitleJson::getArray($value->product->units->title, $this->lang);
                        $balance_base = optional($value)->pivot->balance * intval($value->product->packing);
                    } else {
                        $balance = optional($value)->pivot->balance / intval($value->product->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($value)->pivot->balance;
                    }

                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;

                } else {
                    $balance_base = optional($value)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $balance = optional($value)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($value->product->units->title, $this->lang);
                }

                $data['arr']['product'][$key]['cells']['price'] = $value->pivot->price;

                if (isset($value->pivot->percent)) {
                    $data['arr']['product'][$key]['cells']['percent'] = $value->pivot->percent;
                    $amount_percent = $value->pivot->price * (100 - $value->pivot->percent) / 100;
                    $discount_price = $value->pivot->price - $amount_percent;
                    $data['arr']['product'][$key]['cells']['amount_percent'] = $discount_price;

                    if (isset($balance_base)) {
                        $data['arr']['product'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                    }
                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($amount_percent * $balance_base);
                        $data['sum_product_price'] = $data['sum_product_price'] + ($amount_percent * $balance_base);
                        $data['sum_discount_price'] = $data['sum_discount_price'] + ($discount_price * $balance_base);
                    }
                } else {
                    $data['arr']['product'][$key]['cells']['percent'] = $value->pivot->percent ?? null;
                    $data['arr']['product'][$key]['cells']['amount_percent'] = 0;

                    if (isset($balance_base)) {
                        $data['arr']['product'][$key]['cells']['amount'] = $value->pivot->price * $balance_base;
                    }
                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($value->pivot->price * $balance_base);
                        $data['sum_product_price'] = $data['sum_product_price'] + ($value->pivot->price * $balance_base);
                    }
                }

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                    $data['sum_product_balance'] = $data['sum_product_balance'] + $balance_base;
                }

                $data['arr']['product'][$key]['type'] = Nomenclature::PRODUCT;
            } elseif (isset($value->service)) {
                $data['arr']['service'][$key]['id'] = $value->service->id;
                $data['arr']['service'][$key]['min_price'] = $value->service->min_price;
                $data['arr']['service'][$key]['cells']['convert_id'] = $value->service->convert_id;
                $data['arr']['service'][$key]['cells']['sku'] = $value->service->sku;
                $data['arr']['service'][$key]['cells']['title'] = $value->service->short_title;
                if (isset($value->service->packing) && $value->pivot->is_packing == Yesno::YES) {
                    $data['arr']['service'][$key]['cells']['units'] = Document::PACKING;
                } else {
                    $data['arr']['service'][$key]['cells']['units'] = TitleJson::getArray($value->service->units->title, $this->lang);
                }
                $data['arr']['service'][$key]['cells']['packing'] = $value->service->packing ?? null;
                //$data['arr']['service'][$key]['cells']['percent'] = $value->pivot->percent . '%';
                $data['arr']['service'][$key]['cells']['balance'] = $value->pivot->balance;
                $balance_base = optional($value)->pivot->balance ?? 0;
                $data['arr']['service'][$key]['balance_base'] = $balance_base;
                $balance = optional($value)->pivot->balance ?? 0;
                $data['arr']['service'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($value->service->units->title, $this->lang);
                $data['arr']['service'][$key]['cells']['price'] = $value->pivot->price;

                if (isset($value->pivot->percent)) {
                    $data['arr']['service'][$key]['cells']['percent'] = $value->pivot->percent;
                    $amount_percent = $value->pivot->price * (100 - $value->pivot->percent) / 100;
                    $discount_price = $value->pivot->price - $amount_percent;
                    $data['arr']['service'][$key]['cells']['amount_percent'] = $discount_price;

                    if (isset($balance_base)) {
                        $data['arr']['service'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                    }

                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($amount_percent * $balance_base);
                        $data['sum_service_price'] = $data['sum_service_price'] + ($amount_percent * $balance_base);
                        $data['sum_discount_price'] = $data['sum_discount_price'] + ($discount_price * $balance_base);
                    }
                } else {
                    $data['arr']['service'][$key]['cells']['percent'] = $value->pivot->percent ?? null;
                    $data['arr']['service'][$key]['cells']['amount_percent'] = 0;

                    if (isset($balance_base)) {
                        $data['arr']['service'][$key]['cells']['amount'] = $value->pivot->price * $balance_base;
                    }
                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($value->pivot->price * $balance_base);
                        $data['sum_service_price'] = $data['sum_service_price'] + ($amount_percent * $balance_base);
                    }
                }
//                $data['arr']['service'][$key]['cells']['price'] = $value->pivot->price;
//                $data['arr']['service'][$key]['cells']['amount'] = $value->pivot->price * $balance_base;

                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                    $data['sum_service_balance'] = $data['sum_service_balance'] + $balance_base;
                }
                $data['arr']['service'][$key]['type'] = Nomenclature::SERVICE;
            } elseif (isset($value->kit)) {
                $data['arr']['product'][$key]['id'] = $value->id;
                $data['arr']['product'][$key]['min_price'] = $value->kit->min_price;
                $data['arr']['product'][$key]['cells']['convert_id'] = $value->kit->convert_id;
                $data['arr']['product'][$key]['cells']['title'] = $value->kit->short_title;
                $data['arr']['product'][$key]['cells']['sku'] = $value->kit->sku ?? null;
                $data['arr']['product'][$key]['cells']['units'] = TitleJson::getArray($value->kit->units->title, $this->lang);
                $data['arr']['product'][$key]['cells']['packing'] = $value->kit->packing ?? null;
                // $data['arr']['product'][$key]['cells']['percent'] = $value->pivot->percent . '%';
                $data['arr']['product'][$key]['cells']['balance'] = optional($value)->pivot->balance ?? 0;

                if (isset($value->kit->packing)) {
                    if (isset($value->pivot) && $value->pivot->is_packing == Yesno::YES) {
                        $balance = optional($value)->pivot->balance * intval($value->kit->packing);
                        $units_title = TitleJson::getArray($value->kit->units->title, $this->lang);
                        $balance_base = optional($value)->pivot->balance;
                    } else {
                        $balance = (optional($value)->pivot->balance ?? 0) / intval($value->kit->packing);
                        $units_title = Document::PACKING;
                        $balance_base = optional($value)->pivot->balance ?? 0;
                    }

                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . $units_title;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                } else {
                    $balance_base = optional($value)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['balance_base'] = $balance_base;
                    $balance = optional($value)->pivot->balance ?? 0;
                    $data['arr']['product'][$key]['cells']['balance_units'] = $balance . ' ' . TitleJson::getArray($value->kit->units->title, $this->lang);
                }

                $data['arr']['product'][$key]['cells']['price'] = $value->pivot->price;

                if (isset($value->pivot->percent)) {
                    $data['arr']['product'][$key]['cells']['percent'] = $value->pivot->percent;
                    $amount_percent = $value->pivot->price * (100 - $value->pivot->percent) / 100;
                    $discount_price = $value->pivot->price - $amount_percent;
                    $data['arr']['product'][$key]['cells']['amount_percent'] = $discount_price;

                    if (isset($balance_base)) {
                        $data['arr']['product'][$key]['cells']['amount'] = $amount_percent * $balance_base;
                    }
                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($amount_percent * $balance_base);
                        $data['sum_product_price'] = $data['sum_product_price'] + ($amount_percent * $balance_base);
                        $data['sum_discount_price'] = $data['sum_discount_price'] + ($discount_price * $balance_base);
                    }
                } else {
                    $data['arr']['product'][$key]['cells']['percent'] = $value->pivot->percent ?? null;
                    $data['arr']['product'][$key]['cells']['amount_percent'] = 0;

                    if (isset($balance_base)) {
                        $data['arr']['product'][$key]['cells']['amount'] = $value->pivot->price * $balance_base;
                    }
                    if (isset($value->pivot->price)) {
                        $data['sum_price'] = $data['sum_price'] + ($value->pivot->price * $balance_base);
                        $data['sum_product_price'] = $data['sum_product_price'] + ($value->pivot->price * $balance_base);
                    }
                }
                if (isset($balance_base)) {
                    $data['sum_balance'] = $data['sum_balance'] + $balance_base;
                    $data['sum_product_balance'] = $data['sum_product_balance'] + $balance_base;
                }
                $data['arr']['product'][$key]['type'] = Nomenclature::KIT;
            }
        }
        return $data;
    }

    public function getFailureTable($items)
    {
        $data = [];
        foreach ($items as $key => $item) {

            if (isset($item->categories)) {
                $title_categories = '';
                foreach ($item->categories as $value) {
                    $title_categories = $title_categories . $value->title . '; ';
                }
            }

            $data[$key]['id'] = $item->id;
            $data[$key]['cells']['date'] = $item->date;
            $data[$key]['cells']['convert_id'] = str_pad($item->id, 8, '0', STR_PAD_LEFT);
            $data[$key]['cells']['title'] = $item->title . ' : ' . $this->getDirectoryCore('source_attractions', $item->source_attraction_id);
            $data[$key]['cells']['phone'] = $item->phone ?? null;
            $data[$key]['cells']['categories'] = trim($title_categories) ?? null;
            $data[$key]['cells']['balance'] = $item->nomenclatures->sum('pivot.balance');
            $data[$key]['cells']['percent'] = $item->percent ?? null;
            $data[$key]['cells']['amount'] = $item->nomenclatures->sum('pivot.price');
            $data[$key]['cells']['manager'] = trim($item->employee->full_name) ?? null;
            $data[$key]['cells']['failure_text'] = $item->failure_text ?? null;
        }

        return $data;
    }
}
