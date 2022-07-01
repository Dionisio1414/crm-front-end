<?php

namespace App\Core\Repositories\Traits;

use App\Core\Helpers\TitleJson;
use App\Documents\Models\Orders\DocumentOrdersHeader;
use App\Documents\Models\Purchases\DocumentPurchasesHeader;
use App\Documents\Models\Warehouse\DocumentWarehousesHeader;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Documents\Models\Document;

trait RepositoryDocumentTraits
{
    public function searchTitleByJson($model, $withTable, $s)
    {
        return $model->whereHas($withTable, function ($query) use ($s) {
            $query->where(DB::raw("json_extract(title, '$[*].title')"), 'LIKE', '%' . $s . '%');
            $query->where(DB::raw("json_extract(title, '$[*].code')"), 'LIKE', '%' . $this->lang . '%');
        })->paginate($this->request->paginate);
    }

    public function getTable($data, $modelHeader)
    {

        $new_data = $data->each(function ($item) use ($modelHeader) {
            if (isset($item->document_receipt_stocks)) {
                $item->cells = $item->document_receipt_stocks->cells;
                $type = Document::RECEIPT_STOCKS;
            } elseif (isset($item->document_write_off_stocks)) {
                $item->cells = $item->document_write_off_stocks->cells;
                $type = Document::WRITE_OFF_STOCKS;
            } elseif (isset($item->document_transfer_stocks)) {
                $item->cells = $item->document_transfer_stocks->cells;
                $type = Document::TRANSFER_STOCKS;
            } elseif (isset($item->document_orders)) {
                $item->cells = $this->formatCellsFromValueToIdDocument(
                    DocumentOrdersHeader::class,
                    $modelHeader,
                    $item->document_orders
                );
                $type = Document::ORDERS;
            } elseif (isset($item->document_purchases)) {

                $item->cells = $this->formatCellsFromValueToId(
                    DocumentPurchasesHeader::class,
                    $modelHeader,
                    $item->document_purchases
                );

                $type = Document::PURCHASES;
            }
            if(!isset($type)){
                dd($item);
            }
            $item->type = $type;
            $cells = TitleJson::get($item->cells, $this->lang);
            $new_data = [];
            foreach ($cells as $key => $cell) {
                $headerItem = $modelHeader::find($cell->header_id);
                if (isset($headerItem)) {
                    $new_data[$headerItem->value] = $cell->title;
                }
            }
            unset($item->cells);
            $item->cells = $new_data;
        });

        $data->data = $new_data;

        return $data;
    }

    //workaround, replace with function getTable

    public function getTableDocument($data, $modelHeader)
    {

        $new_data = $data->each(function ($item) use ($modelHeader) {
            if (isset($item->document_receipt_stocks)) {
                //$item->cells = $item->document_receipt_stocks->cells;
                $item->cells = $this->formatCellsFromValueToIdDocument(
                    DocumentWarehousesHeader::class,
                    $modelHeader,
                    $item->document_receipt_stocks
                );
                $type = Document::RECEIPT_STOCKS;
            } elseif (isset($item->document_write_off_stocks)) {
                //$item->cells = $item->document_write_off_stocks->cells;
                $item->cells = $this->formatCellsFromValueToIdDocument(
                    DocumentWarehousesHeader::class,
                    $modelHeader,
                    $item->document_write_off_stocks
                );
                $type = Document::WRITE_OFF_STOCKS;
            } elseif (isset($item->document_transfer_stocks)) {
                //$item->cells = $item->document_transfer_stocks->cells;
                $item->cells = $this->formatCellsFromValueToIdDocument(
                    DocumentWarehousesHeader::class,
                    $modelHeader,
                    $item->document_transfer_stocks
                );
                $type = Document::TRANSFER_STOCKS;
            } elseif (isset($item->document_orders)) {
                //$item->cells = $item->document_transfer_stocks->cells;
                $item->cells = $this->formatCellsFromValueToId(
                    DocumentOrdersHeader::class,
                    $modelHeader,
                    $item->document_orders
                );
                $type = Document::ORDERS;
            } elseif (isset($item->document_purchases)) {

                $item->cells = $this->formatCellsFromValueToId(
                    DocumentPurchasesHeader::class,
                    $modelHeader,
                    $item->document_purchases
                );

                $type = Document::PURCHASES;
            }
            $item->type = $type;
            $cells = TitleJson::get($item->cells, $this->lang);
            $new_data = [];
            foreach ($cells as $key => $cell) {
                $headerItem = $modelHeader::find($cell->header_id);
                if (isset($headerItem)) {
                    $new_data[$headerItem->value] = $cell->title;
                }
            }
            unset($item->cells);
            $item->cells = $new_data;
        });

        $data->data = $new_data;

        return $data;
    }

    public function getOneTable($item, $modelHeader)
    {
        $cells = TitleJson::get($item->cells, $this->lang);
        $new_data = [];
        foreach ($cells as $key => $cell) {
            $headerItem = $modelHeader::find($cell->header_id);
            $new_data[$headerItem->value] = $cell->title;
        }
        unset($item->cells);
        $item->cells = $new_data;

        return $item;
    }

    public function changeCellsFormatBd($model)
    {
        $data = [];
        foreach ($this->request->cells as $key => $cell) {
            $header = $model::where('value', $key)->first();
            if (isset($header)) {
                $jsonCell = [];
                foreach ($this->request->language as $item) {
                    array_push($jsonCell, [
                        'code' => $item['code'],
                        'title' => $cell,
                    ]);
                }

                array_push($data, [
                    'title' => $jsonCell,
                    'header_id' => $header->id
                ]);
            }
        }
        return $data;
    }

    public function changeCellsCustomFormatBd($cells, $model)
    {
        $data = [];
        foreach ($cells as $key => $cell) {
            $header = $model::where('value', $key)->first();
            $jsonCell = [];
            foreach ($this->request->language as $item) {
                array_push($jsonCell, [
                    'code' => $item['code'],
                    'title' => $cell,
                ]);
            }

            array_push($data, [
                'title' => $jsonCell,
                'header_id' => $header->id
            ]);
        }
        return $data;
    }

    public function changeTitleFormatBd($data = null)
    {
        if(!$data){
            $data = $this->request->all();
        }

        $jsonTitle = [];
        foreach ($this->request->language as $item) {
            array_push($jsonTitle, [
                'code' => $item['code'],
                'title' => $data['title'] ?? '',
            ]);
        }
        $data['title'] = $jsonTitle;
        return $data;
    }

    public function getPricingTable($results, $options)
    {
        $data = [];
        foreach ($results as $key => $value) {
            if (isset($value->nomenclatures)) {
                foreach ($value->nomenclatures as $item) {
                    $data_tmp[] = $item;
                }
            } elseif (isset($value->nomenclature)) {
                //foreach ($value->nomenclature as $item) {
                $data_tmp[] = $value->nomenclature;
                //}
            } else {
                $data_tmp[] = $value;
            }
        }
        $data_tmp = collect($data_tmp);


        foreach ($data_tmp->unique('id') as $key => $val) {
            if (isset($val->product)) {
                $data[$key]['id'] = $val->id;
                $data[$key]['cells']['sku'] = $val->product->sku ?? null;
                $data[$key]['cells']['short_title'] = $val->product->short_title;
                $data[$key]['cells']['min_price'] = $val->product->min_price;
                foreach ($val->prices->unique('id') as $price) {
                    if ($options['show_current_price']) {
                        $data[$key]['cells']['current_price_' . $price->id] = $price->pivot->value;
                    }
                    if ($options['show_variance_price']) {
                        $data[$key]['cells']['variance_price_' . $price->id] = 0;
                    }
                    if ($options['show_dependent_price']) {
                        foreach ($price->dependentPrices as $k => $v) {
                            $data[$key]['cells']['dependent_price_' . $v->id] = 0;
                        }
                    }
                    $data[$key]['cells']['new_price_' . $price->id] = 0;
                    $data[$key]['cells']['price_' . $price->id . '_option'] = [
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

    public function formatCellsFromValueToId($fromModelHeader, $toModelHeader, $data)
    {
        $new_cells = collect();
        foreach ($toModelHeader::all() as $key => $to_header) {
            $from_header = $fromModelHeader::where('value', $to_header->value)->first();
            if ($from_header) {
                $item = $data->cells->where('header_id', $from_header->id)->first();
                if (isset($item->title)) {
                    $new_cells->push((object)[
                        'title' => $item->title,
                        'header_id' => $to_header->id
                    ]);
                }
            }
        }

        $new_cells->push((object)[
            'title' => $this->changeTitleFormatBd(['title'=>$data->document_type])['title'] ?? null,
            'header_id' => 4
        ]);

        return $new_cells;
    }

    //workaround, replace with function formatCellsFromValueToId

    public function formatCellsFromValueToIdDocument($fromModelHeader, $toModelHeader, $data)
    {
       // dd($fromModelHeader, $toModelHeader, $data);
        $new_cells = collect();
        foreach ($toModelHeader::all() as $key => $to_header) {
            $from_header = $fromModelHeader::where('value', $to_header->value)->first();
            if ($from_header) {
                $item = $data->cells->where('header_id', $from_header->id)->first();
                if (isset($item->title)) {
                    $new_cells->push((object)[
                        'title' => $item->title,
                        'header_id' => $to_header->id
                    ]);
                }
            }
        }
        //Add items in purchase document
//        $document_core = $this->coreDirectoriesService->getByIdWithLang('documents', 1);
//
//        $new_cells->push((object) [
//            'title'     =>  $document_core,
//            'header_id' =>  4
//        ]);

        return $new_cells;
    }
}
