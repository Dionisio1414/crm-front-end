<?php

namespace App\Characteristics\Repositories;

use App\Characteristics\Models\Characteristic as Model;
use App\Characteristics\Models\CharacteristicValue;
use App\Characteristics\Models\CharacteristicColorValue;
use App\Characteristics\Models\CharacteristicSizeValue;
use App\Core\Model\Yesno;
use App\Core\Repositories\CoreRepository;
use App\Core\Helpers\CheckFile;

/**
 * Class CharacteristicRepository.
 */
class CharacteristicRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCharacteristics()
    {
        return $this->model()->where('archive', 0)->with([
            'characteristicValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            },
            'categories' => function ($q) {
                $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
            }
        ])->orderBy('order', 'ASC')->get();
    }

    public function getCharacteristicsCustom()
    {
        return $this->model()->where([
            ['archive', 0],
            ['id', '!=', 1],
            ['id', '!=', 2],
        ])->with([
            'characteristicValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            },
            'categories' => function ($q) {
                $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
            }
        ])->where('archive', Yesno::NO)->orderBy('order', 'ASC')->get();
    }

    public function getCharacteristicsWithout()
    {
        return $this->model()->where('archive', 0)->orderBy('order', 'ASC')->get();
    }

    public function getCharacteristic($id)
    {
        return $this->model()
            ->with([
                'characteristicValue' => function ($q) {
                    $q->orderBy('order', 'ASC');
                },
                'categories' => function ($q) {
                    $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function getCharacteristicColor($id = 1)
    {
        return $this->model()
            ->with([
                'characteristicColorValue' => function ($q) {
                    $q->orderBy('order', 'ASC');
                },
                'categories' => function ($q) {
                    $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function getCharacteristicSize($id = 2)
    {
        return $this->model()
            ->with([
                'characteristicSizeValue',
                'characteristicValue' => function ($q) {
                    $q->orderBy('order', 'ASC');
                },
                'categories' => function ($q) {
                    $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function createCharacteristic($title)
    {
        return $this->model()->create([
            'title' => $title,
            'order' => '3'
        ]);
    }

    public function createCharacteristicValue($id, $characteristic_values)
    {
        $characteristic_arr = array();
        foreach ($characteristic_values as $key => $value) {
            $characteristic_arr[] = CharacteristicValue::create([
                'characteristic_id' => $id,
                'title' => $value['title'],
                'code' => $value['code'] ?? null,
                'order' => $key
            ]);
        }
        return $characteristic_arr;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function updateCharacteristic($id, $title)
    {
        return $this->model()->find($id)->update([
            'title' => $title
        ]);
    }

    public function updateCharacteristicValue($id, $characteristic_values)
    {
        //CharacteristicValue::where('characteristic_id', $id)->delete();
        $ids = [];
        foreach ($characteristic_values as $value) {
            if (isset($value['id'])) {
                $ids[] = $value['id'];
            }
        }

        CharacteristicValue::whereNotIn('id', $ids)->where('characteristic_id', $id)->delete();

        $characteristic_arr = array();
        foreach ($characteristic_values as $key => $value) {

            if (isset($value['id'])) {
                $characteristic_arr[] = $this->updateCharacteristicsValue($key, $value);
            } else {
                $characteristic_arr[] = $this->addCharacteristicsValue($id, $key, $value);
            }
//            $characteristic_arr[] = CharacteristicValue::create([
//                'characteristic_id' => $id,
//                'title' => $value['title'],
//                'code' => $value['code'] ?? null,
//                'order' => $key
//            ]);
        }
        return $characteristic_arr;
    }

    public function addCharacteristicsValue($id, $key, $value)
    {
        return CharacteristicValue::updateOrCreate(
            ['characteristic_id' => $id, 'title' => $value['title']],
            [
            'characteristic_id' => $id,
            'title' => $value['title'],
            'code' => $value['code'] ?? null,
            'order' => $key,
            'edit' => 0,
        ]);
    }

    public function updateCharacteristicsValue($key, $value)
    {

        return CharacteristicValue::find($value['id'])->update([
            'title' => $value['title'],
            'code' => $value['code'] ?? null,
            'order' => $key,
            'edit' => 0,
        ]);
    }

    public function updateCharacteristicColorValue($id, $characteristic_values)
    {
        //CharacteristicColorValue::where('characteristic_id', $id)->delete();
        if (isset($characteristic_values)) {
            $ids = [];
            foreach ($characteristic_values as $value) {
                if (isset($value['id'])) {
                    $ids[] = $value['id'];
                }
            }

            $getCharacteristicColorValue = CharacteristicColorValue::whereNotIn('id', $ids)->delete();
            $characteristic_arr = array();

            if (isset($characteristic_values)) {
                foreach ($characteristic_values as $key => $value) {
                    // CharacteristicColorValue::whereIn('id', $value['id'])->first();


//                    if (isset($value['selectValue'])) {
//                        $check = (CheckFile::check($value['selectValue']));
//                        if ($check) {
//                            $image = $value['selectValue'];
//                        } else {
//                            $color = $value['selectValue'];
//                        }
//                    } else {
//                        $image = null;
//                        $color = null;
//                    }
                    //CheckFile
                    if (isset($value['id'])) {
                        $characteristic_arr[] = CharacteristicColorValue::find($value['id'])->update([
                            //'characteristic_id' => $id,
                            'title' => $value['title'],
                            'code' => $value['code'] ?? null,
                            'image_id' =>  $value['image_id'] ?? null,
                            'color' => $value['color'] ?? null,
                            'order' => $key
                        ]);
                    } else {
                        $characteristic_arr[] = CharacteristicColorValue::create([
                            'characteristic_id' => $id,
                            'title' => $value['title'],
                            'code' => $value['code'] ?? null,
                            'image_id' =>  $value['image_id'] ?? null,
                            'color' => $value['color'] ?? null,
                            'order' => $key
                        ]);
                    }
                }
            }
            return $characteristic_arr;
        } else {
            CharacteristicColorValue::where('characteristic_id', $id)->delete();
            return 'true';
        }
    }

    public function updateCharacteristicSizeValue($id, $characteristic_values)
    {
        if (isset($characteristic_values)) {
            $ids = [];
            $title = [];
            foreach ($characteristic_values as $value) {
                if (isset($value['id']) && !isset($value['type'])) {
                    $ids[] = $value['id'];
                }
                if (!isset($value['id']) && isset($value['type']) && isset($value['title']) ) {
                    $title[] = $value['title'];
                }
            }

            if($id == 2){
                CharacteristicValue::whereNotIn('title', $title)->where('characteristic_id', $id)->delete();
            }else{
                CharacteristicValue::whereNotIn('id', $ids)->where('characteristic_id', $id)->delete();
            }
            $characteristic_arr = array();
            if (isset($characteristic_values)) {
                foreach ($characteristic_values as $key => $value) {

                    if (isset($value['type'])) {
                        CharacteristicSizeValue::where('title', $value['title'])->update([
                            'check' => (bool)$value['check'],
                        ]);

                        if ($value['check']) {
                            if (isset($value['id'])) {
                                $characteristic_arr[] = $this->updateCharacteristicsValue($key, $value);
                            } else {
                                $characteristic_arr[] = $this->addCharacteristicsValue($id, $key, $value);
                            }
                        }
                    } else {
                        if (isset($value['id'])) {
                            $characteristic_arr[] = $this->updateCharacteristicsValue($key, $value);
                        } else {
                            $characteristic_arr[] = $this->addCharacteristicsValue($id, $key, $value);
                        }
                    }
                }
            }
            return $characteristic_arr;

        } else {
            CharacteristicValue::where('characteristic_id', $id)->delete();
            CharacteristicSizeValue::where('check', 1)->update([
                'check' => 0,
            ]);
            return 'true';
        }
    }

    public function sortCharacteristics($characteristics)
    {
        foreach ($characteristics as $key => $characteristic) {
            $this->model()->where([
                ['id', '!=', 1],
                ['id', '!=', 2],
                ['id', $characteristic['id']]
            ])->update([
                'order' => $key
            ]);
        }
    }

    public function incrementCharacteristic()
    {
        return $this->model()->where([
            ['id', '!=', 1],
            ['id', '!=', 2],
        ])->increment('order');
    }

    public function getCharacteristicsCategory($id)
    {
        return $this->model()->where('archive', 0)->whereHas('categories', function ($q) use ($id) {
            $q->where('category_id', $id);
        })->with([
            'characteristicValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            },
            'characteristicColorValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            }
        ])->orderBy('order', 'ASC')->get();
    }

    public function getSelectedCharacteristicsKits($ids)
    {
        return $this->model()->where('archive', 0)->whereIn('id', $ids)->with([
            'characteristicValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            },
            'characteristicColorValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            }
        ])->orderBy('order', 'ASC')->get();
    }

    public function addCharacteristicValue($id, $title)
    {

        return CharacteristicValue::create([
            'characteristic_id' => $id,
            'title' => $title,
            'edit' => 1,
            'order' => 0
        ]);
    }

    public function addCharacteristicColorValue($id, $value)
    {

        if (isset($value['selectValue'])) {
            $check = (CheckFile::check($value['selectValue']));
            if ($check) {
                $image = $value['selectValue'];
            } else {
                $color = $value['selectValue'];
            }
        }

        //CheckFile
        return CharacteristicColorValue::create([
            'characteristic_id' => $id,
            'title' => $value['title'],
            'code' => $value['code'] ?? null,
            'image' => $image ?? null,
            'color' => $color ?? null,
            'order' => 0
        ]);
    }

    public function toArchive($items)
    {
        $this->model()->whereIn('id', $items)->update([
            'archive' => '1'
        ]);
    }

}
