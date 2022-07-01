<?php

namespace App\Core\Helpers;

class ProductsTable
{
    public static function products($data, $lang)
    {
        $object= [];
        foreach($data as $key => $product){
            foreach ($product->products as $key_pr => $value){
                $value->each(function ($items) use ($lang) {

                    foreach ($items->title as $item) {
                        if($item['code'] == $lang){
                            return $items->title = $item['title'];
                        }
                    }
                });
                $object[$key]['cells']['short_title'] = $value->short_title;
                $object[$key]['cells']['sku'] = $value->sku;
                $object[$key]['cells']['units'] = $value->units->cells;
            }
        }
        return $object;

//        return $data->each(function ($items) use ($code) {
//            foreach ($items->title as $item) {
//                if($item['code'] == $code){
//                    return $items->title = $item['title'];
//                }
//            }
//        });
    }

    public static function product($data)
    {
        $object= [];

        $object['cells']['short_title'] = $data->short_title;
        $object['cells']['sku'] = $data->sku;
        $object['cells']['units'] = $data->units->cells;

        return $object;

//        return $data->each(function ($items) use ($code) {
//            foreach ($items->title as $item) {
//                if($item['code'] == $code){
//                    return $items->title = $item['title'];
//                }
//            }
//        });
    }
}
