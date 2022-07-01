<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\WarehouseService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    use ApiResponder;

    public $warehouseService;

    /**
     * Create a new controller instance.
     *
     * @param WarehouseService $warehouseService
     */
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses",
     *      tags={"Warehouses"},
     *      summary="Get All Warehouses",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Warehouses",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/GroupWarehousesWithWarehouses",
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function index(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->getWarehouses());
    }

    /**
     * @OA\Post(
     *      path="/products/warehouses",
     *      tags={"Warehouses"},
     *      summary="Add Warehouses",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change data",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
     *                    type="string",
     *                    example="title",
     *               ),
     *              @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example={"380999999999", "380999999999"},
     *              ),
     *              @OA\Property(
     *                    property="street",
     *                    title="Title street",
     *                    type="string",
     *                    example="street",
     *               ),
     *               @OA\Property(
     *                    property="number_house",
     *                    title="Title number house",
     *                    type="string",
     *                    example="12-b",
     *               ),
     *              @OA\Property(
     *                    property="country_id",
     *                    title="Country id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="region_id",
     *                    title="Region id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="city_id",
     *                    title="City id",
     *                    type="int",
     *                    example=1,
     *               ),
     *              @OA\Property(
     *                    property="warehouse_type_id",
     *                    title="Warehouse type id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="warehouse_group_id",
     *                    title="Warehouse group id",
     *                    type="int",
     *                    example=1,
     *               ),
     *              @OA\Property(
     *                    property="employee_id",
     *                    title="Employee id",
     *                    type="int",
     *                    example=1,
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/Warehouses"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function store()
    {
        return $this->successResponse($this->warehouseService->storeWarehouse());
    }

    /**
     * @OA\Post(
     *      path="/products/warehouses/create-warehouse-group",
     *      tags={"Warehouses"},
     *      summary="Add Warehouses",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title and array warahouse id",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                  property="parent_id",
     *                  title="Parent ID",
     *                  type="integer",
     *                  example="id parent category(1) or 0 is not parent warehouse groups",
     *               ),
     *               @OA\Property(
     *                      property="data",
     *                      @OA\Items(
     *                          @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                          ),
     *                      ),
     *                )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/WarehouseGroup"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeWarehouseGroup()
    {
        return $this->successResponse($this->warehouseService->storeWarehouseGroup());
    }

    /**
     * @OA\Put(
     *      path="/products/warehouses/{id}",
     *      tags={"Warehouses"},
     *      summary="Update Warehouse",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
     *                    type="string",
     *                    example="title",
     *               ),
     *              @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example={"380999999999", "380999999999"},
     *              ),
     *              @OA\Property(
     *                    property="street",
     *                    title="Title street",
     *                    type="string",
     *                    example="street",
     *               ),
     *               @OA\Property(
     *                    property="number_house",
     *                    title="Title number house",
     *                    type="string",
     *                    example="12-b",
     *               ),
     *              @OA\Property(
     *                    property="country_id",
     *                    title="Country id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="region_id",
     *                    title="Region id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="city_id",
     *                    title="City id",
     *                    type="int",
     *                    example=1,
     *               ),
     *              @OA\Property(
     *                    property="warehouse_type_id",
     *                    title="Warehouse type id",
     *                    type="int",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="warehouse_group_id",
     *                    title="Warehouse group id",
     *                    type="int",
     *                    example=1,
     *               ),
     *              @OA\Property(
     *                    property="employee_id",
     *                    title="Employee id",
     *                    type="int",
     *                    example=1,
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/Warehouses"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */
    public function update($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->editWarehouse($id));
    }

    /**
     * @OA\Put(
     *      path="/products/warehouses/update-warehouse-group/{id}",
     *      tags={"Warehouses"},
     *      summary="Update Warehouse",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title and array warahouse id",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title warahouse",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                  property="parent_id",
     *                  title="Parent ID",
     *                  type="integer",
     *                  example="id parent category(1) or 0 is not parent warehouse groups",
     *               ),
     *               @OA\Property(
     *                      property="data",
     *                      @OA\Items(
     *                          @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                          ),
     *                      ),
     *                )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/WarehouseGroup"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */
    public function updateWarehouseGroup($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->updateWarehouseGroup($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/{id}",
     *      tags={"Warehouses"},
     *      summary="Get warehouse id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/WarehouseFinished"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function show($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showWarehouse($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/get-warehouse-group/{id}",
     *      tags={"Warehouses"},
     *      summary="Get warehouse group id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/GroupWarehousesWithWarehouses"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showWarehouseGroup($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showWarehouseGroup($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/products-all",
     *      tags={"Warehouses"},
     *      summary="Get all products",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showProductsAll(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showProductsAll());
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/products/{id}",
     *      tags={"Warehouses"},
     *      summary="Get warehouse group id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/kits-all",
     *      tags={"Warehouses"},
     *      summary="Get all kits",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showKitsAll(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showKitsAll());
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/kits/{id}",
     *      tags={"Warehouses"},
     *      summary="Get products warehouse id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Table Data Suppliers",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showKits($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->showKits($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/get-warehouses",
     *      tags={"Warehouses"},
     *      summary="Get warehouses",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Warehouses",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/WarehousesWithGroup",
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getWarehouses(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->getWarehousesAll());
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/get-warehouses-groups",
     *      tags={"Warehouses"},
     *      summary="Get warehouses",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Warehouses",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/GroupWarehousesWithoutWarehouses",
     *                 ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getWarehousesGroups(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->getWarehousesGroups());
    }

    /**
     * @OA\Post(
     *      path="/products/warehouses/toArchiveWarehouse",
     *      tags={"Warehouses"},
     *      summary="Get warehouses",
     *      security={},
     *        @OA\RequestBody(
     *          description="Change array id warehouses",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function toArchiveWarehouse(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->toArchiveWarehouse());
    }

    /**
     * @OA\Post(
     *      path="/products/warehouses/toArchiveWarehouseGroup",
     *      tags={"Warehouses"},
     *      summary="Get warehouses",
     *      security={},
     *        @OA\RequestBody(
     *          description="Change array id warehouses group",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function toArchiveWarehouseGroup(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->toArchiveWarehouseGroup());
    }

    /**
     * @OA\Put(
     *      path="/products/warehouses/move-warehouses/{id}",
     *      tags={"Warehouses"},
     *      summary="Warehouses move in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="ID",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function moveWarehouses($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->moveWarehouses($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/fill-products-stocks/{id}",
     *      tags={"Warehouses"},
     *      summary="Fill in the balance in the warehouse",
     *      security={},
     *      @OA\Parameter(
     *         name="price_id",
     *         description="Price ID",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="date",
     *           description="Date",
     *           required=true,
     *           in="query",
     *             example="2021-01-20 11:59:01",
     *           @OA\Schema(
     *                type="date"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Fill in the balance in the warehouse",
     *                @OA\Property(
     *                 property="nomenclatures",
     *                 title="Get Table Data",
     *                  @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="sum_price",
     *                    title="Sum price",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="sum_balance",
     *                    title="Sum balance",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function fillProductsStocks($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->fillProductsStocks($id));
    }

    /**
     * @OA\Put(
     *      path="/products/warehouses/choose-default-warehouse/{id}",
     *      tags={"Warehouses"},
     *      summary="Warehouse choose default",
     *      security={},
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function chooseDefaultWarehouse($domain, $id): JsonResponse
    {
        return $this->successResponse($this->warehouseService->chooseDefaultWarehouse($id));
    }

    /**
     * @OA\Get(
     *      path="/products/warehouses/get-default-warehouse",
     *      tags={"Warehouses"},
     *      summary="Get default warehouse",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                  @OA\Property(
     *                      property="id",
     *                      title="Id",
     *                      example=1,
     *                      type="int"
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      title="Title",
     *                      example="Title",
     *                      type="string"
     *                  )
     *              )
     *          )
     *      ),
     *
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getDefaultWarehouse(): JsonResponse
    {
        return $this->successResponse($this->warehouseService->getDefaultWarehouse());
    }


//    public function store(Request $request): JsonResponse
//    {
//        $this->authorService->getAuthor($request->author_id);
//
//        return $this->successResponse($this->bookService->createBook($request->all()));
//    }
//
//    public function show($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->getBook($book));
//    }
//
//    public function update(Request $request, $book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->editBook(($request->all()),
//            $book));
//    }
//
//    public function destroy($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->deleteBook($book));
//    }
}
