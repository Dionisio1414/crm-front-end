<?php

namespace App\Properties\Repositories;

use App\Core\Model\Yesno;
use App\Properties\Models\Property as Model;
use App\Properties\Models\PropertyValue;
use App\Core\Repositories\CoreRepository;

/**
 * Class PropertyRepository.
 */
class PropertyRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getProperties()
    {
        return $this->model()->where('archive', 0)
            ->with([
                'propertyValue' => function ($q) {
                    $q->orderBy('order', 'ASC');
                },
                'categories' => function ($q) {
                    $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
                }
            ])->orderBy('order', 'ASC')->get();
    }

    public function getPropertiesWithout()
    {
        return $this->model()->where('archive', 0)->whereNotIn('id', [1,2])->orderBy('order', 'ASC')->get();
    }

    public function getProperty($id)
    {
        return $this->model()->where('archive', 0)
            ->with([
                'propertyValue' => function ($q) {
                    $q->orderBy('order', 'ASC');
                },
                'categories' => function ($q) {
                    $q->where('archive', Yesno::NO)->without('children')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function createProperty($title)
    {
        return $this->model()->create([
            'title' => $title,
            'order' => '3'
        ]);
    }

    public function createPropertyValue($id, $property_values)
    {
        $property_arr = array();
        foreach ($property_values as $key => $value) {
            $property_arr[] = PropertyValue::create([
                'property_id' => $id,
                'title' => $value,
                'order' => $key
            ]);
        }
        return $property_arr;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function updateProperty($id, $title)
    {
        return $this->model()->find($id)->update([
            'title' => $title
        ]);
    }

    public function updatePropertyValue($id, $property_values)
    {
        //PropertyValue::where('property_id', $id)->delete();
        $ids = [];
        foreach ($property_values as $value){
            if(isset($value['id'])){
                $ids[] = $value['id'];
            }
        }

        PropertyValue::whereNotIn('id', $ids)->where('property_id', $id)->delete();
        if (isset($property_values)) {
            $property_arr = array();
            foreach ($property_values as $key => $value) {
                if(isset($value['id'])){
                    $property_arr[] = PropertyValue::find($value['id'])->update([
                        //'property_id' => $id,
                        'title' => $value['title'],
                        'order' => $key
                    ]);
                }else{
                    $property_arr[] = PropertyValue::create([
                        'property_id' => $id,
                        'title' => $value['title'],
                        'order' => $key
                    ]);
                }
            }
        }
        return $property_arr;
    }

    public function sortProperties($properties)
    {
        foreach ($properties as $key => $property) {
            $this->model()->where([
                ['id', '!=', 1],
                ['id', '!=', 2],
                ['id', $property['id']]
            ])->update([
                'order' => $key
            ]);
        }
    }

    public function toArchive($properties)
    {
        $this->model()->whereIn('id', $properties)->update([
            'archive' => '1'
        ]);
    }

    public function incrementProperty()
    {
        return $this->model()->where([
            ['id', '!=', 1],
            ['id', '!=', 2],
        ])->increment('order');
    }

    public function getPropertiesCategory($id)
    {
        return $this->model()->where('archive', Yesno::NO)->whereHas('categories', function ($q) use ($id) {
            $q->where('category_id', $id);
        })->with([
            'propertyValue' => function ($q) {
                $q->orderBy('order', 'ASC');
            }
        ])->orderBy('order', 'ASC')->get();
    }

    public function addPropertyValue($id, $title)
    {
        return PropertyValue::create([
            'property_id' => $id,
            'title' => $title,
            'order' => 0
        ]);
    }

    public function getPropertyValue($id)
    {
        return PropertyValue::find($id);
    }


    public function editPropertyValue($id, $title)
    {
        PropertyValue::find($id)->update([
            'title' => $title,
        ]);

        return $this->getPropertyValue($id);
    }


}
