<?php

namespace App\Services\Controllers\Leads;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\LeadsService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadsController extends Controller
{
    use ApiResponder;

    public $leadsService;

    /**
     * Create a new controller instance.
     *
     * @param LeadsService $leadsService
     */
    public function __construct(LeadsService $leadsService)
    {
        $this->leadsService = $leadsService;
    }

    /**
     * @OA\Get(
     *      path="/leads/table",
     *      tags={"Leads"},
     *      summary="Get Table Data Leads",
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
     *           name="lead_status_id",
     *           description="Lead status id",
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
     *                 title="Get Table Data Leads",
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
        return $this->successResponse($this->leadsService->getTable());
    }

    /**
     * @OA\Get(
     *      path="/leads/failure-table",
     *      tags={"Leads"},
     *      summary="Get failure Table Data Leads",
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
     *                 title="Get Table Data Leads",
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

    public function getFailureTable(): JsonResponse
    {
        return $this->successResponse($this->leadsService->getFailureTable());
    }

    /**
     * @OA\Get(
     *      path="/leads/list/{id}",
     *      tags={"Leads"},
     *      summary="Get Lead",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Leads",
     *                 ref="#/components/schemas/GetLead"
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
        return $this->successResponse($this->leadsService->show($id));
    }

    /**
     * @OA\Get(
     *      path="/leads/list/{id}/edit",
     *      tags={"Leads"},
     *      summary="Get Lead edit",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Leads",
     *                 ref="#/components/schemas/GetLead"
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

    public function edit($domain, $id): JsonResponse
    {
        return $this->successResponse($this->leadsService->edit($id));
    }

    /**
     * @OA\Get(
     *      path="/leads/store-order-and-customer/{id}",
     *      tags={"Leads"},
     *      summary="Get Lead",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Leads",
     *                 ref="#/components/schemas/GetLead"
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

    public function storeOrderAndCustomer($domain, $id): JsonResponse
    {
        return $this->successResponse($this->leadsService->storeOrderAndCustomer($id));
    }


    /**
     * @OA\Post(
     *      path="/leads/list",
     *      tags={"Leads"},
     *      summary="Create Data Lead",
     *      @OA\RequestBody(
     *          description="Create Data Lead",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/LeadParams"
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
     *                     ref="#/components/schemas/Directories"
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
        return $this->successResponse($this->leadsService->createItems());
    }

    /**
     * @OA\Put(
     *      path="/leads/list/{id}",
     *      tags={"Leads"},
     *      summary="Update Data",
     *      @OA\RequestBody(
     *          description="Update Data Lead",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/LeadParams"
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
     *                     ref="#/components/schemas/Lead"
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
        return $this->successResponse($this->leadsService->editItems($id));
    }

    /**
     * @OA\Put(
     *      path="/leads/headers",
     *      tags={"Leads"},
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
        return $this->successResponse($this->leadsService->updateHeader());
    }

    /**
     * @OA\Post(
     *      path="/leads/headers/toDefault",
     *      tags={"Leads"},
     *      summary="Leads header to Default",
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
        return $this->successMessage($this->leadsService->defaultHeader(),200);
    }

    /**
     * @OA\Post(
     *      path="/leads/toArchive",
     *      tags={"Leads"},
     *      summary="Leads to Archive",
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
        return $this->successResponse($this->leadsService->toArchive());
    }

    /**
     * @OA\Post(
     *      path="/leads/toFailure",
     *      tags={"Leads"},
     *      summary="Leads to Failure",
     *      @OA\RequestBody(
     *          description="Leads to Failure",
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

    public function toFailure(): JsonResponse
    {
        return $this->successResponse($this->leadsService->toFailure());
    }

    /**
     * @OA\Post(
     *      path="/leads/outFailure",
     *      tags={"Leads"},
     *      summary="Leads out Failure",
     *      @OA\RequestBody(
     *          description="Leads out Failure",
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

    public function outFailure(): JsonResponse
    {
        return $this->successResponse($this->leadsService->outFailure());
    }
    //validation

    /**
     * @OA\Post(
     *      path="/leads/store-async-validations",
     *      tags={"Leads"},
     *      summary="Validate create lead",
     *      @OA\RequestBody(
     *          description="Change phone,title,partner_inn,partner_edrpou",
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
        return $this->successResponse($this->leadsService->storeAsyncValidations());
    }

    /**
     * @OA\Post(
     *      path="/leads/update-async-validations",
     *      tags={"Leads"},
     *      summary="Update Updated Leads",
     *      @OA\RequestBody(
     *          description="Change phone,title,partner_inn,partner_edrpou",
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
        return $this->successResponse($this->leadsService->updateAsyncValidations());
    }
}
