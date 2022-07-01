<?php

namespace App\Services\Controllers\Suppliers;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\SupplierGroupService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    use ApiResponder;

    public $supplierGroupService;

    /**
     * Create a new controller instance.
     *
     * @param SupplierGroupService $supplierGroupService
     */
    public function __construct(SupplierGroupService $supplierGroupService)
    {
        $this->supplierGroupService = $supplierGroupService;
    }

    /**
     * @OA\Get(
     *      path="/suppliers/groups",
     *      tags={"Suppliers"},
     *      summary="Get All Groups Suppliers",
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
     *                 title="All Groups Suppliers",
     *                 @OA\Property(
     *                    property="list",
     *                    title="Get List Data",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/SupplierGroups"
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
        return $this->successResponse($this->supplierGroupService->getGroups());
    }

    /**
     * @OA\Post(
     *      path="/suppliers/groups",
     *      tags={"Suppliers"},
     *      summary="Create Group",
     *      @OA\RequestBody(
     *          description="Create Group",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="parent_id",
     *                  title="Parent Id",
     *                  example=0,
     *                  type="int"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  title="Title",
     *                  example="Title",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="order",
     *                  title="Order",
     *                  example=0,
     *                  type="int"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Group",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/SupplierGroup"
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
        return $this->successResponse($this->supplierGroupService->createGroup());
    }

    /**
     * @OA\Put(
     *      path="/suppliers/groups/{id}",
     *      tags={"Suppliers"},
     *      summary="Update Group",
     *      @OA\RequestBody(
     *          description="Update Group",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="parent_id",
     *                  title="Parent Id",
     *                  example=0,
     *                  type="int"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  title="Title",
     *                  example="Title",
     *                  type="string"
     *              ),
     *              @OA\Property(
     *                  property="order",
     *                  title="Order",
     *                  example=0,
     *                  type="int"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Group",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/SupplierGroup"
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

    public function update($domain,$id): JsonResponse
    {
        return $this->successResponse($this->supplierGroupService->updateGroup($id));
    }

    /**
     * @OA\Post(
     *      path="/suppliers/groups/toArchive",
     *      tags={"Suppliers"},
     *      summary="Supplier Group to Archive",
     *      @OA\RequestBody(
     *          description="Supplier Group to Archive",
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
        return $this->successResponse($this->supplierGroupService->toArchive());
    }
}
