<?php

namespace App\Warehouses\Repositories;

use App\Core\Helpers\TitleJson;
use App\Core\Model\Yesno;
use App\Core\Repositories\Traits\RepositoryTraits;
use App\Directories\Models\Cities\DirectoryCity;
use App\Directories\Models\Countries\DirectoryCountry;
use App\Directories\Models\Regions\DirectoryRegion;
use App\Warehouses\Models\Warehouse as Model;
use App\Warehouses\Models\WarehouseGroup;
use App\Core\Repositories\CoreRepository;

/**
 * Class WarehouseRepository.
 */
class WarehouseRepository extends CoreRepository
{
    use RepositoryTraits;

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getWarehouseWithout($id)
    {
        return $this->model->find($id);
    }

    public function getWarehouse($id)
    {
        $warehouse = $this->model()->with([
            'warehouseGroups',
            'countries',
            'regions',
            'cities',
            'employees'
        ])->findOrFail($id);

        $data = [
            'id' => $warehouse->id,
            'title' => $warehouse->title,
            'phone' => $warehouse->phone,
            'street' => $warehouse->street,
            'number_house' => $warehouse->number_house,
            'title_country' => TitleJson::getArray($warehouse->countries->title, $this->lang),
            'title_region' => TitleJson::getArray($warehouse->regions->title_region, $this->lang),
            'title_city' => TitleJson::getArray($warehouse->regions->title_city, $this->lang),
            'title_warehouse_type' => $this->getDirectoryCore('type_warehouse', $warehouse->warehouse_type_id),
            'title_warehouse_groups' => $warehouse->warehouseGroups->title ?? null,
            'title_employee' => $warehouse->employees->full_name,
            'created_at' => $warehouse->created_at,
        ];

        return $data;
    }

    public function getWarehouseGroup($id)
    {
        return WarehouseGroup::with([
            'warehouse' => function ($q) {
                $q->where('archive', 0)->orderBy('order', 'ASC');
            }
        ])->findOrFail($id);
    }

    public function getWarehouses()
    {
        return $this->model()->where('archive', 0)->doesntHave('warehouseGroups')->get();
    }

    public function getWarehousesWithGroups()
    {
        return $this->model()->where('archive', 0)->with([
            'warehouseGroups' => function ($q) {
                $q->where('archive', 0)->orderBy('order', 'ASC');
            }
        ])->get();
    }

    public function getWarehousesGroupsWithoutWarehouses()
    {
        return WarehouseGroup::where('archive', 0)->where('parent_id', 0)->with([
            'children' => function ($q) {
                $q->where('archive', '0')->orderBy('order', 'ASC');
            }
        ])->get();
    }

    public function getWarehousesGroups()
    {
        return WarehouseGroup::where('archive', 0)->where('parent_id', 0)->with([
            'children' => function ($q) {
                $q->where('archive', '0')->orderBy('order', 'ASC')->with([
                    'warehouse' => function ($q) {
                        $q->where('archive', 0)->orderBy('order', 'ASC');
                    }
                ]);
            },
            'warehouse' => function ($q) {
                $q->where('archive', 0)->orderBy('order', 'ASC');
            }
        ])->get();
    }

//    public function getWarehouseProducts($id)
//    {
//        return $this->model()->where('id', $id)->with('nomenclatures')->get();
//    }

    public function createWarehouse()
    {
        $item = $this->model()->create($this->request->all());

        $item = $this->getParamTitleAttributesSingleItem(
            $item,
            $this->_paramsAddress(),
            $this->_paramsAddress(false)
        );

        return $item;
    }

    public function createWarehouseGroup()
    {
        $warehouse = $this->model()->warehouseGroups()->create($this->request->all());
        if(isset($this->request->data)){
            $this->model()->whereIn('id', $this->request->data)->update([
                'warehouse_group_id' => $warehouse->id
            ]);
        }



        return $warehouse;
    }

    public function updateWarehouse($id)
    {
        $item = $this->model()->find($id)->update(array_merge($this->request->all(), ['warehouse_group_id' => $this->request->warehouse_group_id ?? null]));

        $item = $this->getParamTitleAttributesSingleItem(
            $item,
            $this->_paramsAddress(),
            $this->_paramsAddress(false)
        );

        return $item;
    }

    public function updateWarehouseGroup($id)
    {
        if (isset($this->request->data)) {
            $this->model()->where('warehouse_group_id', $id)->update([
                'warehouse_group_id' => null
            ]);

            $this->model()->whereIn('id', $this->request->data)->update([
                'warehouse_group_id' => $id
            ]);
        }

        WarehouseGroup::find($id)->update($this->request->all());

        return $this->getWarehouseGroup($id);
    }

    public function toArchiveWarehouse()
    {
        return $this->model()->whereIn('id', $this->request->data)->update([
            'archive' => Yesno::YES
        ]);
    }

    public function toArchiveWarehouseGroup()
    {
        return WarehouseGroup::whereIn('id', $this->request->data)->update([
            'archive' => Yesno::YES
        ]);
    }

    public function moveWarehouses($id)
    {
        return $this->model()->whereIn('id', $this->request->data)->update([
            'warehouse_group_id' => $id
        ]);
    }

    public function getDocumentReceiptStockWarehouse($id)
    {
        return $this->model()->where('id', $id)->with([
            'documents' => function ($q) {
                $q->with(['document_receipt_stocks']);
            }
        ])->get();
    }

    public function chooseDefaultWarehouse($id)
    {
        $this->model()->where('is_default', Yesno::YES)->update([
            'is_default' => Yesno::NO
        ]);

        return $this->model()->find($id)->update([
            'is_default' => Yesno::YES
        ]);
    }

    public function getDefaultWarehouse()
    {
        return $this->model()->where('archive', Yesno::NO)->where('is_default', Yesno::YES)->first();
    }

    public function _paramsAddress($is_params = true)
    {
        if($is_params){
            return [
                'country_title' => 'country_id',
                'region_title'  => 'region_id',
                'city_title'    => 'city_id',
            ];
        }

        return [
            DirectoryCountry::class,
            DirectoryRegion::class,
            DirectoryCity::class,
        ];
    }
}
