<?php

namespace App\Services\Controllers\Suppliers;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\SuppliersService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SuppliersController extends Controller
{
    use ApiResponder;

    public $suppliersService;

    /**
     * Create a new controller instance.
     *
     * @param SuppliersService $suppliersService
     */
    public function __construct(SuppliersService $suppliersService)
    {
        $this->suppliersService = $suppliersService;
    }

    /**
     * @OA\Get(
     *      path="/suppliers/list",
     *      tags={"Suppliers"},
     *      summary="Get List Suppliers",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get List Suppliers",
     *                 @OA\Property(
     *                    property="list",
     *                    title="Get List Data",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                      @OA\Property(
     *                          property="full_names",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                              @OA\Property(
     *                                  property="id",
     *                                  title="Id",
     *                                  example=1,
     *                                  type="integer",
     *                              ),
     *                              @OA\Property(
     *                                  property="title",
     *                                  title="Title",
     *                                  example="Title",
     *                                  type="string",
     *                              )
     *                          )
     *                      )
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

    public function index(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->getList());
    }

    /**
     * @OA\Get(
     *      path="/suppliers/table",
     *      tags={"Suppliers"},
     *      summary="Get Table Data Suppliers",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *           name="group_id",
     *           description="Group id",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="int"
     *           )
     *      ),
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

    public function getTable(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->getTable());
    }

    /**
     * @OA\Post(
     *      path="/suppliers/list",
     *      tags={"Suppliers"},
     *      summary="Create Data Supplier",
     *      @OA\RequestBody(
     *          description="Create Data Supplier",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/SupplierParams"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Supplier"
     *                 )
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

    public function store(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->createItems());
    }

    /**
     * @OA\Put(
     *      path="/suppliers/list/{id}",
     *      tags={"Suppliers"},
     *      summary="Update Data",
     *      @OA\RequestBody(
     *          description="Update Data Sapplier",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/SupplierParams"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Supplier"
     *                 )
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
        return $this->successResponse($this->suppliersService->editItems($id));
    }

    /**
     * @OA\Put(
     *      path="/suppliers/headers",
     *      tags={"Suppliers"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/DirectoriesHeader"
     *                 )
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

    public function updateHeader(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->updateHeader());
    }

    /**
     * @OA\Post(
     *      path="/suppliers/headers/toDefault",
     *      tags={"Suppliers"},
     *      summary="Suppliers header to Default",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
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

    public function defaultHeader(): JsonResponse
    {
        return $this->successMessage($this->suppliersService->defaultHeader(),200);
    }

    /**
     * @OA\Post(
     *      path="/suppliers/toArchive",
     *      tags={"Suppliers"},
     *      summary="Suppliers to Archive",
     *      @OA\RequestBody(
     *          description="Directory to Archive",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                  )
     *               )
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

    public function toArchive(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->toArchive());
    }

    /**
     * @OA\Post(
     *      path="/suppliers/changeGroups",
     *      tags={"Suppliers"},
     *      summary="Change Suppliers Group",
     *      @OA\RequestBody(
     *          description="Change Suppliers Group",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                       ),
     *                       @OA\Property(
     *                          property="group_id",
     *                          type="integer",
     *                          example=1,
     *                       ),
     *                  )
     *               )
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

    public function changeGroups(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->changeGroups());
    }

    //validation

    /**
     * @OA\Post(
     *      path="/suppliers/store-async-validations",
     *      tags={"Suppliers"},
     *      summary="Validate create supplier",
     *      @OA\RequestBody(
     *          description="Change phone,title_supplier,partner_inn,partner_edrpou",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="validate",
     *                          @OA\Property(
     *                              property="key_validate",
     *                              title="key validate",
     *                              example="title",
     *                              type="string"
     *                          ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="success",
     *                 title="true",
     *                 example="title",
     *              )
     *          )
     *       ),
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

    public function storeAsyncValidations(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->storeAsyncValidations());
    }

    /**
     * @OA\Post(
     *      path="/suppliers/update-async-validations",
     *      tags={"Suppliers"},
     *      summary="Update Updated Suppliers",
     *      @OA\RequestBody(
     *          description="Change phone,title_supplier,partner_inn,partner_edrpou",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="validate",
     *                       @OA\Property(
     *                           property="key_validate",
     *                           title="key validate",
     *                           example="title",
     *                           type="string"
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="success",
     *                 title="true",
     *                 example="title",
     *              )
     *          )
     *       ),
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

    public function updateAsyncValidations(): JsonResponse
    {
        return $this->successResponse($this->suppliersService->updateAsyncValidations());
    }
}
