<?php

namespace App\Core\Helpers;

class DirectoryDefaultValue
{
    public static function getDefaultTitle($data, $modelHeader, $header_value)
    {
        $header = $modelHeader::where('value', $header_value)->first('id');
        $title = [];
        if(is_array($data)){
            return '';
        }
        foreach ($data as $key => $cell) {
            if ($cell->header_id == $header->id) {
                $title = $cell->title;
            }
        }
        return $title;
    }

    public static function getDefaultTitleArray($data, $modelHeader, $header_value)
    {
        $header = $modelHeader::where('value', $header_value)->first('id');
        $title = [];
        foreach ($data as $key => $cell) {
            if ($cell['header_id'] == $header['id']) {
                $title = $cell['title'];
            }
        }
        return $title;
    }
}
