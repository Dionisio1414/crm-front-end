<?php

namespace App\Core\Helpers\Csv;

use Maatwebsite\Excel\Facades\Excel;

class ImportCsv
{
    public static function _getData($path)
    {
        $data = Excel::toArray('',$path);
        $new_data = [];
        $keys = [];
        foreach ($data[0] as $key=>$csv){
            if($csv[0] == 'id') {
                $keys = $csv;
            }else{
                foreach ($csv as $key2=>$item){
                    $new_data[$key][$keys[$key2]] = $item;
                }
            }
        }

        return $new_data;
    }

    public static  function _getDataJsonTitle($path)
    {
        $data = self::_getData($path);

        $new_data = [];
        foreach ($data as $key=>$item) {
            foreach ($item as $key2=>$item2){
                if(stripos($key2, 'title') !== false){
                    $new_data[$key]['title'][] = [
                        'title' => $item2,
                        'code'  => str_replace("title_", "", $key2),
                    ];
                }else{
                    $new_data[$key][$key2] = $item2;
                }
            }
            if(isset($new_data[$key]['title'])){
                $new_data[$key]['title'] = json_encode($new_data[$key]['title']);
            }
        }
        return $new_data;
    }
}




