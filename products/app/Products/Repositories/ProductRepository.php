<?php

namespace App\Categories\Repositories;

use App\Categories\Models\Product as Model;
use App\Core\Repositories\CoreRepository;

/**
 * Class ProductRepository.
 */
class ProductRepository extends CoreRepository
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
            ])->orderBy('order', 'ASC')->get();
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    public function getCategory($id)
    {
        return $this->model()
            ->with([
                'properties' => function ($q) {
                    $q->where('archive', '0')->with('propertyValue')->orderBy('order', 'ASC');
                },
                'characteristics' => function ($q) {
                    $q->with('characteristicValue')->orderBy('order', 'ASC');
                },
                'parent' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                },
            ])->findOrFail($id);
    }

    public function getCategoryWithChildren($id)
    {
        return $this->model()
            ->with([
                'children' => function ($q) {
                    $q->where('archive', '0')->orderBy('order', 'ASC');
                },
                'properties' => function ($q) {
                    $q->where('archive', '0')->with('propertyValue')->orderBy('order', 'ASC');
                },
                'characteristics' => function ($q) {
                    $q->with('characteristicValue')->orderBy('order', 'ASC');
                }
            ])->findOrFail($id);
    }

    public function getCategoryWithout($id)
    {
        return $this->model()->findOrFail($id);
    }

    public function createCategory($request)
    {

        $category = $this->model()->create([
            'title' => $request['title'],
            'desc' => $request['desc'] ?? null,
            'image' => $request['image'] ?? null,
            'parent_id' => $request['parent_id'] ?? 0,
            'status' => $request['status'] ?? 0,
            'order' => 0
        ]);

        if (isset($request['properties'])) {
            foreach ($request['properties'] as $key => $property) {
                $category->properties()->attach($property['id'], ['order' => $key]);
            }
        }

        if (isset($request['characteristics'])) {
            foreach ($request['characteristics'] as $key => $characteristic) {
                $category->characteristics()->attach($characteristic['id'], ['order' => $key]);
            }
        }

        if (isset($request['categories'])) {
            $this->model()->where('parent_id', $category->id)->
            update([
                'parent_id' => 0
            ]);
            foreach ($request['categories'] as $key => $cat) {
                $this->model()->find($cat['id'])->update([
                    'parent_id' => $category->id,
                    'order' => $key
                ]);
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
            'image' => $request['image'] ?? $category->image,
            'parent_id' => $request['parent_id'] ?? $category->parent_id,
            'status' => $request['status'] ?? $category->status,
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
            $this->model()->where('parent_id', $category->id)->
            update([
                'parent_id' => 0
            ]);
            foreach ($request['categories'] as $key => $cat) {
                $this->model()->find($cat['id'])->update([
                    'parent_id' => $category->id,
                    'order' => $key
                ]);
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
            if (isset($category_parent->properties)) {

                foreach ($category_parent->properties as $property) {
                    $category_children->properties()->sync($property['id']);
                }
            }

            if (isset($category_parent->characteristics)) {

                foreach ($category_parent->characteristics as $characteristic) {
                    $category_children->characteristics()->sync($characteristic['id']);
                }
            }
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
        $this->model()->whereIn('id', $categories)->update([
            'archive' => '1'
        ]);
    }

}
