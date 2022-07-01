<?php

namespace App\Categories\Repositories;

use App\Categories\Models\Category as Model;
use App\Core\Helpers\TitleJson;
use App\Core\Model\Yesno;
use App\Core\Repositories\CoreRepository;
use Carbon\Carbon;

/**
 * Class CategoryRepository.
 */
class CategoryRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCategories()
    {
        return $this->model()->where('archive', 0)->where('parent_id', 0)
            ->with([
                'children' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                },
                'image'
            ])->orderBy('order', 'ASC')->get();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getCategory($id)
    {
        $category = $this->model()
            ->with([
                'properties' => function ($q) {
                    $q->where('archive', Yesno::NO)->with(['propertyValue'])->orderBy('order', 'ASC');
                },
                'characteristics' => function ($q) {
                    $q->where('archive', Yesno::NO)->with(['characteristicValue'])->orderBy('order', 'ASC');
                },
                'parent' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                },
                'unit' => function ($q) {
                    $q->without('cells');
                },
                'image'
            ])->find($id);

        if (isset($category->unit->title)) {
            $category['unit_title'] = TitleJson::getArray($category->unit->title, $this->lang);
            $category->unit->makeHidden('cells');
        }

        //dd($category);
        return $category;
    }

    public function getCategoryWithChildren($id)
    {
        $category = $this->model()
            ->with([
                'children' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                },
                'properties' => function ($q) {
                    $q->where('archive', '0')->with('propertyValue')->orderBy('order', 'ASC');
                },
                'characteristics' => function ($q) {
                    $q->with('characteristicValue')->orderBy('order', 'ASC');
                },
                'unit' => function ($q) {
                    $q->without('cells');
                },
                'image'
            ])->findOrFail($id);

        $category['unit_title'] = TitleJson::getArray($category->unit->title, $this->lang);
        $category->unit->makeHidden('cells');

        return $category;
    }

    public function getCategoryChildren($id)
    {
        return $this->model()
            ->with([
                'children' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function getCategoryWithout($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function createCategory($request, $unit_default_value)
    {
        $default_property_ids = [1, 2];

        $category = $this->model()->create([
            'title' => $request['title'],
            'desc' => $request['desc'] ?? null,
            'image_id' => $request['image_id'] ?? null,
            'parent_id' => $request['parent_id'] ?? 0,
            'status' => $request['status'] ?? 0,
            'unit_id' => $request['unit_id'] ?? $unit_default_value->id,
            'order' => 0
        ]);


        foreach ($default_property_ids as $key => $id) {
            $category->properties()->attach($id, ['order' => $key]);
        }

        if (isset($request['properties'])) {
            foreach ($request['properties'] as $key => $property) {
                if ($property['id'] != 1 && $property['id'] != 2) {
                    $category->properties()->attach($property['id'], ['order' => $key]);
                }
            }
        }

        if (isset($request['characteristics'])) {
            foreach ($request['characteristics'] as $key => $characteristic) {
                $category->characteristics()->attach($characteristic['id'], ['order' => $key]);
            }
        }

        if (isset($request['categories'])) {
            $this->model()->where('parent_id', $category->id)->update([
                'parent_id' => 0
            ]);
            foreach ($request['categories'] as $key => $cat) {
                $parent_category = $this->getCategoryWithout($cat['id']);
                $parent_category->update([
                    'parent_id' => $category->id,
                    'order' => $key
                ]);
                if (isset($request['properties'])) {
                    foreach ($request['properties'] as $k => $property) {
                        $parent_category->properties()->attach($property['id'], ['order' => $k]);
                    }
                }
                if (isset($request['characteristics'])) {
                    foreach ($request['characteristics'] as $k => $characteristic) {
                        $parent_category->characteristics()->attach($characteristic['id'], ['order' => $k]);
                    }
                }
//                $this->model()->find($cat['id'])->update([
//                    'parent_id' => $category->id,
//                    'order' => $key
//                ]);
            }
        }

        return $category;
    }

    public function updateCategory($id, $request)
    {
        $category = $this->getCategoryWithout($id);
        $this->model()->find($id)->update([
            'title' => $request['title'],
            'desc' => $request['desc'] ?? $category->desc,
            'image_id' => $request['image_id'] ?? $category->image_id,
            'parent_id' => $request['parent_id'] ?? $category->parent_id,
            'status' => $request['status'] ?? $category->status,
            'unit_id' => $request['unit_id'] ?? $category->unit_id,
        ]);

        if (isset($request['properties'])) {
            $category->properties()->detach();
            foreach ($request['properties'] as $key => $property) {
                $category->properties()->attach($property['id'], ['order' => $key]);
            }
        }

        if (isset($request['characteristics'])) {
            $category->characteristics()->detach();
            foreach ($request['characteristics'] as $key => $characteristic) {
                $category->characteristics()->attach($characteristic['id'], ['order' => $key]);
            }
        }

        if (isset($request['categories'])) {
            $this->model()->where('parent_id', $category->id)->update([
                'parent_id' => 0
            ]);
            foreach ($request['categories'] as $key => $cat) {
                $parent_category = $this->getCategoryWithout($cat['id']);
                $parent_category->update([
                    'parent_id' => $category->id,
                    'order' => $key
                ]);
                if (isset($request['properties'])) {
                    foreach ($request['properties'] as $k => $property) {
                        $parent_category->properties()->attach($property['id'], ['order' => $k]);
                    }
                }
                if (isset($request['characteristics'])) {
                    foreach ($request['characteristics'] as $k => $characteristic) {
                        $parent_category->characteristics()->attach($characteristic['id'], ['order' => $k]);
                    }
                }
//                $this->model()->find($cat['id'])->update([
//                    'parent_id' => $category->id,
//                    'order' => $key
//                ]);
            }
        }

        return $this->getCategoryWithChildren($id);
    }

    public function parentCategories($categories)
    {

        foreach ($categories as $key => $category) {
            $this->model()->find($category['id'])->update([
                'parent_id' => $category['parent_id']
            ]);

            $category_children = $this->getCategory($category['id']);

            $category_parent = $this->getCategory($category['parent_id']);
            // dd($category_parent, $category_parent->properties, $category_parent->characteristics);
            if (isset($category_parent->properties)) {
                $category_children->properties()->sync($category_parent->properties);
//                foreach ($category_parent->properties as $property) {
//                }
            }

            if (isset($category_parent->characteristics)) {
                $category_children->characteristics()->sync($category_parent->characteristics);
//                foreach ($category_parent->characteristics as $characteristic) {
//                }
            }
        }
    }

    public function sortItemCategories($items)
    {
        foreach ($items as $key => $item) {
            $this->model()->where(
                'id', $item['id']
            )->update([
                'order' => $key
            ]);
        }
    }

    public function incrementCategory()
    {
        return $this->model()->increment('order');
    }

    public function visibilityCategory($id, $request)
    {
        $this->model()->find($id)->update([
            'status' => $request['status'],
        ]);

        return $this->getCategoryWithChildren($id);
    }

    public function toArchive($categories)
    {
        $get_categories = $this->model()->whereIn('id', $categories)->get();
        foreach ($get_categories as $category) {
            $title = $category->title . '-backup-' . Carbon::now();
            $category->update([
                'title' => $title
            ]);
        }

        return $this->model()->whereIn('id', $categories)->update([
            'archive' => 1
        ]);
    }


    public function createCharacteristicPropertiesInCategory($id, $products)
    {
        $category = $this->getCategory($id);
        $characteristic_value = [];

        foreach ($products as $product) {

            if (!empty($product->propertyValue)) {
                foreach ($product->propertyValue as $property) {
                    $check_property = $this->model()->where('id', $id)->whereHas('properties', function ($q) use ($property) {
                        $q->where('property_id', $property->property_id);
                    })->first();
                    if (is_null($check_property)) {
                        $category->properties()->attach($property->property_id);
                    }
                }
            }

            if (!empty($product->characteristicValue)) {
                foreach ($product->characteristicValue as $key => $characteristic) {
                    $check_characteristic = $this->model()->where('id', $id)->whereHas('characteristics', function ($q) use ($characteristic) {
                        $q->where('characteristic_id', $characteristic->characteristic_id);
                    })->first();
                    if (is_null($check_characteristic)) {
                        $category->characteristics()->attach($characteristic->characteristic_id);
                    }
                }
            }

            if (!empty($product->characteristicColorValue)) {
                foreach ($product->characteristicColorValue as $key => $characteristic) {
                    $check_characteristic_color = $this->model()->where('id', $id)->whereHas('characteristics', function ($q) use ($characteristic) {
                        $q->where('characteristic_id', $characteristic->characteristic_id);
                    })->first();
                    if (is_null($check_characteristic_color)) {
                        $category->characteristics()->attach($characteristic->characteristic_id);
                    }
                }
            }
        }
    }
}
