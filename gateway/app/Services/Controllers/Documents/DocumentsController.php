<?php

namespace App\Services\Controllers\Documents;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\DocumentsService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    use ApiResponder;

    public $documentsService, $provider;

    /**
     * Create a new controller instance.
     *
     * @param DocumentsService $documentsService
     */
    public function __construct(DocumentsService $documentsService)
    {
        $this->documentsService = $documentsService;
        $this->provider = request()->document;
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/list",
     *      tags={"Documents"},
     *      summary="Get List Documents",
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
        return $this->successResponse($this->documentsService->getList($this->provider));
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/table",
     *      tags={"Documents"},
     *      summary="Get Table Data Documents",
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
        return $this->successResponse($this->documentsService->getTable($this->provider));
    }

    /**
     * @OA\Put(
     *      path="/documents/{name_document}/headers",
     *      tags={"Document"},
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
        return $this->successResponse($this->documentsService->updateHeader($this->provider));
    }

    /**
     * @OA\Put(
     *      path="/documents/document-nomenclatures-headers",
     *      tags={"Document"},
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

    public function updateDocumentNomenclaturesHeader(): JsonResponse
    {
        return $this->successResponse($this->documentsService->updateDocumentNomenclaturesHeader());
    }

    /**
     * @OA\Put(
     *      path="/documents/document-warehouses-headers",
     *      tags={"Document"},
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

    public function updateDocumentWarehousesHeader(): JsonResponse
    {
        return $this->successResponse($this->documentsService->updateDocumentWarehousesHeader());
    }

    /**
     * @OA\Put(
     *      path="/documents/document-all-headers",
     *      tags={"Document"},
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

    public function updateDocumentAllHeader(): JsonResponse
    {
        return $this->successResponse($this->documentsService->updateDocumentAllHeader());
    }

    /**
     * @OA\Post(
     *      path="/documents/toArchive",
     *      tags={"Document"},
     *      summary="Document to Archive",
     *      @OA\RequestBody(
     *          description="Document to Archive",
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
        return $this->successResponse($this->documentsService->toArchive());
    }

    /**
     * @OA\Post(
     *      path="/documents/{name_document}/store-document",
     *      tags={"Document"},
     *      summary="Create document",
     *      @OA\RequestBody(
     *          description="Create Document",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="balance_base",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="is_packing",
     *                          type="boolean",
     *                       ),
     *                       @OA\Property(
     *                          property="price",
     *                          type="integer",
     *                          example=100,
     *                       ),
     *                  )
     *               ),
     *               @OA\Property(
     *                       property="comments",
     *                       type="string",
     *                       example="comment",
     *               ),
     *               @OA\Property(
     *                       property="warehouse_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="price_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="currency_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="user_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="organization_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_actual",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_scheduled",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               ),
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

    public function storeDocument(): JsonResponse
    {
        return $this->successResponse($this->documentsService->storeDocument($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/documents/{name_document}/store-capitalized-document",
     *      tags={"Document"},
     *      summary="Create capitalized document",
     *      @OA\RequestBody(
     *          description="Create capitalized Document",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="balance_base",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                      @OA\Property(
     *                          property="is_packing",
     *                          type="boolean",
     *                       ),
     *                      @OA\Property(
     *                          property="price",
     *                          type="integer",
     *                          example=100,
     *                       ),
     *                  )
     *               ),
     *               @OA\Property(
     *                       property="comments",
     *                       type="string",
     *                       example="comment",
     *               ),
     *               @OA\Property(
     *                       property="warehouse_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="currency_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="user_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="organization_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="price_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *              @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_actual",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_scheduled",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
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

    public function storeCapitalizedDocument(): JsonResponse
    {
        return $this->successResponse($this->documentsService->storeCapitalizedDocument($this->provider));
    }

    /**
     * @OA\Put(
     *      path="/documents/{name_document}/update-document/{id}",
     *      tags={"Document"},
     *      summary="Update document",
     *      @OA\RequestBody(
     *          description="Update Document",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="balance_base",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="is_packing",
     *                          type="boolean",
     *                       ),
     *                       @OA\Property(
     *                          property="price",
     *                          type="integer",
     *                          example=1000,
     *                       ),
     *                  )
     *               ),
     *               @OA\Property(
     *                       property="comments",
     *                       type="string",
     *                       example="comment",
     *               ),
     *               @OA\Property(
     *                       property="warehouse_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="currency_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="user_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="organization_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="price_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *              @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_actual",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_scheduled",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
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

    public function updateDocument($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->updateDocument($this->provider, $id));
    }

    /**
     * @OA\Put(
     *      path="/documents/{name_document}/capitalized-document/{id}",
     *      tags={"Document"},
     *      summary="Update document",
     *      @OA\RequestBody(
     *          description="Update Document",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                       @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="balance_base",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                       @OA\Property(
     *                          property="is_packing",
     *                          type="boolean",
     *                       ),
     *                       @OA\Property(
     *                          property="price",
     *                          type="integer",
     *                          example=5,
     *                       ),
     *                  )
     *               ),
     *               @OA\Property(
     *                       property="comments",
     *                       type="string",
     *                       example="comment",
     *               ),
     *               @OA\Property(
     *                       property="warehouse_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="currency_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="user_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="organization_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *               @OA\Property(
     *                       property="price_id",
     *                       type="integer",
     *                       example=1,
     *               ),
     *              @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_actual",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
     *               @OA\Property(
     *                       property="receipt_date_scheduled",
     *                       type="date",
     *                       example="2020-12-16 17:04:12",
     *               ),
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

    public function capitalizedDocument($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->capitalizedDocument($this->provider, $id));
    }

    /**
     * @OA\Put(
     *      path="/documents/{name_document}/canceled-capitalized-document/{id}",
     *      tags={"Document"},
     *      summary="Canceled capitalized document",
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

    public function canceledCapitalizedDocument($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->canceledCapitalizedDocument($this->provider, $id));
    }

    /**
     * @OA\Get(
     *      path="/documents/get-warehouse-documents/{id}",
     *      tags={"Document"},
     *      summary="Get Documents in Warehouse",
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
     *                 title="Get Documents in Warehouse",
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

    public function getWarehouseDocuments($domain, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->getWarehouseDocuments($id));
    }

    /**
     * @OA\Get(
     *      path="/documents/purchases/get-activity-purchases",
     *      tags={"Document"},
     *      summary="Get activity purchases",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get activity purchases",
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

    public function getActivityPurchases(): JsonResponse
    {
        return $this->successResponse($this->documentsService->getActivityPurchases());
    }

    /**
     * @OA\Get(
     *      path="/documents/orders/get-shipment-orders",
     *      tags={"Document"},
     *      summary="Get shipment orders",
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get shipment orders",
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

    public function getShipmentOrders(): JsonResponse
    {
        return $this->successResponse($this->documentsService->getShipmentOrders());
    }


    /**
     * @OA\Get(
     *      path="/documents/get-warehouse-documents-all",
     *      tags={"Document"},
     *      summary="Get Documents All",
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
     *                 title="Get Documents in Warehouse",
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

    public function getWarehouseDocumentsAll(): JsonResponse
    {
        return $this->successResponse($this->documentsService->getWarehouseDocumentsAll());
    }

    /**
     * @OA\Get(
     *      path="/documents/get-documents-all",
     *      tags={"Document"},
     *      summary="Get Documents All in Tab",
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
     *                 title="Get Documents in Warehouse",
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

    public function getDocumentsAll(): JsonResponse
    {
        return $this->successResponse($this->documentsService->getDocumentsAll());
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/get-document/{id}",
     *      tags={"Document"},
     *      summary="Get Document with selected nomenclatures",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Documents in Warehouse",
     *                 ref="#/components/schemas/GetDocument"
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

    public function getDocumentTable($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->getDocumentTable($this->provider, $id));
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/get-select-document/{id}",
     *      tags={"Document"},
     *      summary="Output of selected products in the document",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Output of selected products in the document",
     *                 ref="#/components/schemas/GetSelectDocument"
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

    public function getSelectDocumentTable($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->documentsService->getSelectDocumentTable($this->provider, $id));
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/open-basket",
     *      tags={"Document"},
     *      summary="get open basket",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Documents in Warehouse",
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

    public function openBasketOrders($domain, $provider): JsonResponse
    {
        return $this->successResponse($this->documentsService->openBasket($this->provider));
    }

    /**
     * @OA\Get(
     *      path="/documents/{name_document}/general-count-basket",
     *      tags={"Document"},
     *      summary="get general count basket",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="count basket orders",
     *                 type="integer",
     *                 example="2",
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

    public function generalCountBasket($domain, $provider): JsonResponse
    {
        return $this->successResponse($this->documentsService->generalCountBasket($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/documents/{name_document}/update-nomenclatures-in-basket",
     *      tags={"Document"},
     *      summary="add or update basket",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Documents in Warehouse",
     *                 @OA\Property(
     *                    property="id",
     *                    title="nomeclature id",
     *                    type="integer",
     *                     example="2",
     *                  ),
     *                  @OA\Property(
     *                    property="count",
     *                    title="nomeclature id",
     *                    type="integer",
     *                    example="2",
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

    public function updateNomenclaturesInBasket($domain, $provider): JsonResponse
    {
        return $this->successResponse($this->documentsService->updateNomenclaturesInBasket($this->provider));
    }

    /**
     * @OA\Delete(
     *      path="/documents/{name_document}/clear-basket",
     *      tags={"Document"},
     *      summary="clear basket",
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

    public function clearBasket($domain, $provider): JsonResponse
    {
        return $this->successResponse($this->documentsService->clearBasket($this->provider));
    }
}
