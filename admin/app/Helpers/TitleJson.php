<?php

namespace App\Helpers;

class TitleJson
{
    public static function get($data, $language_id)
    {
        return $data->each(function ($items) use ($language_id) {
            foreach ($items->title as $item) {
                if($item['language_id'] == $language_id){
                    return $items->title = $item['title'];
                }
            }
        });
    }

    public static function getArray($data, $language_id)
    {
        foreach ($data as $item){
            if($item['language_id'] == $language_id){
                return $item['title'];
            }
        }
    }
}
