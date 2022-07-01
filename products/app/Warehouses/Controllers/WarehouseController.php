<?php

namespace App\Warehouses\Controllers;

use App\Warehouses\Repositories\WarehouseRepository;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $warehouseRepository;
    private $nomenclatureRepository;

    public function __construct(WarehouseRepository $warehouseRepository, NomenclatureRepository $nomenclatureRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->warehouseRepository = $warehouseRepository;
        $this->nomenclatureRepository = $nomenclatureRepository;
    }

    public function index(): JsonResponse
    {
        $warehousesGroups = $this->warehouseRepository->getWarehousesGroups()->toArray();
        $array = $this->recursiveWarehousesGroups($warehousesGroups);
        $warehousesGroups = collect($array);

        $warehouses = $this->warehouseRepository->getWarehouses();
        $merged = $warehousesGroups->push(['not_warehouse' => $warehouses]);
        return $this->successResponse($merged, Response::HTTP_OK);
    }

    public function recursiveWarehousesGroups($items)
    {
        if(isset($items)){
            foreach ($items as $key=>$groups) {

                if(isset($groups['warehouse']) && count($groups['warehouse']) > 0){
                    foreach ($groups['warehouse'] as $key2=>$warehouse){
                        $items[$key]['children'][] = $warehouse;
                    }
                    unset($items[$key]['warehouse']);
                }

                if(isset($groups['children']) && count($groups['children']) > 0)
                {
                    $items[$key]['children'] = $this->recursiveWarehousesGroups($items[$key]['children']);
                }
            }
            return $items;
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:warehouses|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $warehouse = $this->warehouseRepository->createWarehouse();
            return $this->successResponse($warehouse, Response::HTTP_OK);
        }
    }

    public function storeWarehouseGroup(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:warehouse_groups|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $warehouse_group = $this->warehouseRepository->createWarehouseGroup();
            return $this->successResponse($warehouse_group, Response::HTTP_OK);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:warehouses,title,' . $id . 'id|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->warehouseRepository->updateWarehouse($id);
            $warehouse = $this->warehouseRepository->getWarehouseWithout($id);
            return $this->successResponse($warehouse, Response::HTTP_OK);
        }
    }

    public function updateWarehouseGroup(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:warehouse_groups,title,' . $id . 'id|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $warehouse = $this->warehouseRepository->updateWarehouseGroup($id);
            return $this->successResponse($warehouse, Response::HTTP_OK);
        }
    }

    public function show($id): JsonResponse
    {
        $warehouse = $this->warehouseRepository->getWarehouse($id);
        return $this->successResponse($warehouse, Response::HTTP_OK);
    }

    public function showWarehouseGroup($id): JsonResponse
    {
        $warehouseGroup = $this->warehouseRepository->getWarehouseGroup($id);
        return $this->successResponse($warehouseGroup, Response::HTTP_OK);
    }

    public function showProducts($id): JsonResponse
    {
        $products = $this->nomenclatureRepository->getWarehouseProducts($id);
        return $this->successResponse($products, Response::HTTP_OK);
    }

    public function showProductsAll(): JsonResponse
    {
        $products = $this->nomenclatureRepository->getWarehouseProductsAll();
        return $this->successResponse($products, Response::HTTP_OK);
    }

    public function showKits($id): JsonResponse
    {
        $kits = $this->nomenclatureRepository->getWarehouseKits($id);
        return $this->successResponse($kits, Response::HTTP_OK);
    }

    public function showKitsAll(): JsonResponse
    {
        $kits = $this->nomenclatureRepository->getWarehouseKitsAll();
        return $this->successResponse($kits, Response::HTTP_OK);
    }

    public function fillProductsStocks($id): JsonResponse
    {
        $products = $this->nomenclatureRepository->fillProductsStocks($id);
        return $this->successResponse($products, Response::HTTP_OK);
    }

    //list Warehouses
    public function getWarehouses(): JsonResponse
    {
        $warehouses = $this->warehouseRepository->getWarehousesWithGroups();
        return $this->successResponse($warehouses, Response::HTTP_OK);
    }

    //list Groups Warehouses
    public function getWarehousesGroups(): JsonResponse
    {
        $warehousesGroups = $this->warehouseRepository->getWarehousesGroupsWithoutWarehouses();
        return $this->successResponse($warehousesGroups, Response::HTTP_OK);
    }

    public function toArchiveWarehouse(): JsonResponse
    {
        $this->warehouseRepository->toArchiveWarehouse();
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function toArchiveWarehouseGroup(): JsonResponse
    {
        $this->warehouseRepository->toArchiveWarehouseGroup();
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function moveWarehouses($id)
    {
        $this->warehouseRepository->moveWarehouses($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function chooseDefaultWarehouse($id)
    {
        $this->warehouseRepository->chooseDefaultWarehouse($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function getDefaultWarehouse()
    {
        $warehouse = $this->warehouseRepository->getDefaultWarehouse();
        if (isset($warehouse)) {
            $result = [
                'id' => $warehouse->id,
                'title' => $warehouse->title,
            ];
            return $this->successResponse($result, Response::HTTP_OK);
        } else {
            return $this->successResponse('', Response::HTTP_OK);
        }
    }


    // DOCUMENTS

    public function getDocumentReceiptStockWarehouse($id)
    {
        $warehouses = $this->warehouseRepository->getDocumentReceiptStockWarehouse($id);
        return $this->successResponse($warehouses, Response::HTTP_CREATED);
    }


}
