<?php

namespace App\Core\Helpers;

class TitleJson
{
    public static function get($data, $code)
    {
        return $data->each(function ($items) use ($code) {
            foreach ($items->title as $item) {
                if($item['code'] == $code){
                    return $items->title = $item['title'];
                }
            }
        });
    }

    public static function getArray($data, $code)
    {
        foreach ($data as $item){
            if($item['code'] == $code){
                return $item['title'] ?? '';
            }
        }
    }

    public static function getObjectLanguage($data, $language_id)
    {
        foreach ($data as $item){
            if($item->language_id == $language_id){
                return $item->title;
            }
        }
    }

}
