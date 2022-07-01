<?php

namespace App\Core\Repositories\Traits;

use App\Core\Helpers\TitleJson;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

trait RepositoryTraits
{
    public function searchTitleByJson($model, $withTable, $s)
    {
        return $model->whereHas($withTable, function ($query) use ($s) {
            $query->where(DB::raw("json_extract(title, '$[*].title')"), 'LIKE', '%' . $s . '%');
            $query->where(DB::raw("json_extract(title, '$[*].code')"), 'LIKE', '%' . $this->lang . '%');
        })->paginate($this->request->paginate);
    }

    //    public function searchTitleWithParameterByJson($model, $withTable, $s, $column_name, $column_value)
//    {
//        return $model->where($column_name, $column_value)->whereHas($withTable, function ($query) use ($s) {
//            $query->where(DB::raw("json_extract(title, '$[*].title')"), 'LIKE', '%' . $s . '%');
//            $query->where(DB::raw("json_extract(title, '$[*].code')"), 'LIKE', '%' . $this->lang . '%');
//        })->paginate($this->request->paginate);
//    }

    public function searchTitleByJsonRelaion($model, $withTable, $s)
    {
        return $model->whereHas($withTable, function ($query) use ($s) {
            $query->where(DB::raw("json_extract(title, '$[*].title')"), 'LIKE', '%' . $s . '%');
            $query->where(DB::raw("json_extract(title, '$[*].code')"), 'LIKE', '%' . $this->lang . '%');
        });
    }

    public function whereRelation($model, $param, $where)
    {
        return $model->where($param, $where);
    }

    public function whereCellByHeaderRelation($model, $modelHeader, $header_value, $where)
    {
        // Get header id by value
        $headerItem = $modelHeader::where('value', $header_value)->first();
        if($id = $headerItem->id){
        return $model->whereHas('cells', function ($query) use ($id, $where) {
                    $query->where(DB::raw("json_extract(title, '$[*].title')"), 'LIKE', '%' . $where . '%');
                    $query->where('header_id', $id);
               });
        }

        return $model;
    }

    public function getTable($data, $modelHeader)
    {
        $new_data = $data->each(function ($item) use ($modelHeader) {
            $cells = TitleJson::get($item->cells, $this->lang);
            $new_data = [];
            foreach ($cells as $key => $cell) {
                $headerItem = $modelHeader::find($cell->header_id);
                $new_data[$headerItem->value] = $cell->title;
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

    public function changeCellsCustomFormatBd($cells, $model)
    {
        $data = [];
        foreach ($cells as $key => $cell) {
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

    public function changeTitleFormatBd()
    {
        $data = $this->request->all();
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

    public function changeCustomFieldFormatBd($field)
    {
        $data = $this->request->all();
        $jsonTitle = [];
        foreach ($this->request->language as $item) {
            array_push($jsonTitle, [
                'code' => $item['code'],
                'title' => $data[$field] ?? '',
            ]);
        }
        $data[$field] = $jsonTitle;
        return $data[$field];
    }

    public function getCountryCityRegion($id, $type, $model)
    {
        if ($id) {
            $item = $model::find($id)->$type;
            if ($item) {
                return TitleJson::getArray($item, $this->lang);
            } else {
                return null;
            }
        }
        return null;
    }

    public function getDirectoryCore($type, $id)
    {

        if ($id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById($type, $id))) {
                return TitleJson::getObjectLanguage($directory_service->data->title, $this->lang_id);
            }
        }

        return null;
    }

    public function getDirectoryCoreList($type)
    {
        if ($directory_service = json_decode($this->coreDirectoriesService->getList($type))) {
            foreach ($directory_service->data as $directory){
                $rezult[] = [
                    'id' => $directory->directory_id,
                    'title' => TitleJson::getObjectLanguage($directory->title, $this->lang_id)
                ];
            }
            return $rezult;
        }

        return null;
    }

    public function getDirectoryImageCore($type, $id)
    {
        if ($id) {
            if ($directory_service = json_decode($this->coreDirectoriesService->getById($type, $id))) {
                return $directory_service->data->thumbnail;
            }
        }

        return null;
    }

    /* Change one cell in table (directory, etc) */
    public function updateCellsOneTable($model, $modelHeader, $modelDetail, $title_cell, $new_value = null)
    {
        $items = $model->get();

        $data = [
            $title_cell => $new_value
        ];

        $data = $this->changeCellsCustomFormatBd($data, $modelHeader);
        $data = $data[0];

        foreach ($items as $item){
            foreach ($item->cells as $cell){
                if($cell->header_id == $data['header_id']){
                    $modelDetail::find($cell->id)->update($data);
                }
            }
        }
    }

    public function getParamFromJson($data, $params, $eloquent = null)
    {
        $new_data = $data->each(function ($item) use ($params, $eloquent) {

            foreach ($params as $param){
                if($eloquent){
                    if($item->$eloquent->$param){
                        $new_data = TitleJson::getArray($item->$eloquent->$param->title, $this->lang);
                        unset($item->$eloquent->$param);
                        $item->$eloquent->$param = $new_data;
                    }
                }else{
                    if($item->$param){
                        $new_data = TitleJson::getArray($item->$param->title, $this->lang);
                        unset($item->$param);
                        $item->$param = $new_data;
                    }
                }
            }

            return $item;
        });

        $data->data = $new_data;

        return $data;
    }

    public function getParamTitleAttributes($data, $params, $models)
    {
        foreach ($data as $item)
        {
            $this->_calculateDataAttributes($item, $params, $models);
        }

        return $data;
    }

    public function getParamTitleAttributesSingleItem($data, $params, $models)
    {
        $this->_calculateDataAttributes($data, $params, $models);
        return $data;
    }

    public function _calculateDataAttributes($item, $params, $models)
    {
        $count = 0;
        foreach ($params as $paramTitle=>$paramId)
        {
            $object = null;

            if($paramTitle == 'additional'){
                foreach ($paramId as $key=>$itemAdditional){
                    if($item->$key){
                        $countAdditional = 0;
                        foreach ($itemAdditional as $keyAdd=>$additional){
                            $array_explode = explode('.', $keyAdd);
                            if(isset($item->$key[$array_explode[0]])){
                                foreach ($item->$key[$array_explode[0]] as $relation){
                                    $object = $models[$countAdditional]::where('id', $relation->$additional)->first();
                                    $this->_updateObject($relation, $object, $array_explode[1]);
                                }
                            }else{
                                foreach ($item->$key as $relation){
                                    $object = $models[$countAdditional]::where('id', $relation[$array_explode[0]]->$additional)->first();
                                    $this->_updateObject($relation[$array_explode[0]], $object, $array_explode[1]);
                                }
                            }


                            $countAdditional++;
                        }
                    }
                }
            }else if($item->$paramId){
                $object = $models[$count]::where('id', $item->$paramId)->first();
                $this->_updateObject($item, $object, $paramTitle);
            }

            $count++;
        }
    }

    public function _updateObject($item, $object, $paramTitle)
    {
        if($object){
            $item->$paramTitle = TitleJson::getArray($object->title, $this->lang);
        }else{
            $item->$paramTitle = null;
        }
    }
}
