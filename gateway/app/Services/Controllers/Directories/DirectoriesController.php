<?php

namespace App\Services\Controllers\Directories;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\DirectoriesService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DirectoriesController extends Controller
{
    use ApiResponder;

    public $directoriesService, $provider;

    /**
     * Create a new controller instance.
     *
     * @param DirectoriesService $directoriesService
     */
    public function __construct(DirectoriesService $directoriesService)
    {
        $this->directoriesService = $directoriesService;
        $this->provider = request()->directory;
    }

    /**
     * @OA\Get(
     *      path="/directories/{name_directory}/list",
     *      tags={"Directory"},
     *      summary="Get List Directories",
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
     *                 title="Get List Directories",
     *                 @OA\Property(
     *                    property="list",
     *                    title="Get List Data",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesList"
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
        return $this->successResponse($this->directoriesService->getList($this->provider));
    }

    /**
     * @OA\Get(
     *      path="/directories/{name_directory}/table",
     *      tags={"Directory"},
     *      summary="Get Table Data Directories",
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
     *                 title="Get Table Data Directories",
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
        return $this->successResponse($this->directoriesService->getTable($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/directories/{name_directory}/list",
     *      tags={"Directory"},
     *      summary="Create Data",
     *      @OA\RequestBody(
     *          description="Create Data",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/Directories"
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
        return $this->successResponse($this->directoriesService->createItems($this->provider));
    }

    /**
     * @OA\Put(
     *      path="/directories/{name_directory}/list/{id}",
     *      tags={"Directory"},
     *      summary="Update Data",
     *      @OA\RequestBody(
     *          description="Update Data",
     *          required=true,
     *          @OA\JsonContent(
     *             ref="#/components/schemas/Directories"
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

    public function update($domain, $provider, $id): JsonResponse
    {
        return $this->successResponse($this->directoriesService->editItems($this->provider, $id));
    }

    /**
     * @OA\Put(
     *      path="/directories/{name_directory}/headers",
     *      tags={"Directory"},
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
        return $this->successResponse($this->directoriesService->updateHeader($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/directories/{name_directory}/headers/toDefault",
     *      tags={"Directory"},
     *      summary="Directory header to Default",
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
        return $this->successMessage($this->directoriesService->defaultHeader($this->provider),200);
    }

    /**
     * @OA\Post(
     *      path="/directories/{name_directory}/toArchive",
     *      tags={"Directory"},
     *      summary="Directory to Archive",
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
        return $this->successResponse($this->directoriesService->toArchive($this->provider));
    }

    //validation

    /**
     * @OA\Post(
     *      path="/directories/{name_directory}/store-async-validations",
     *      tags={"Directory"},
     *      summary="Validate create Directories",
     *      @OA\RequestBody(
     *          description="Change param",
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
        return $this->successResponse($this->directoriesService->storeAsyncValidations($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/directories/{name_directory}/update-async-validations",
     *      tags={"Directory"},
     *      summary="Update Directories",
     *      @OA\RequestBody(
     *          description="Change param",
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
        return $this->successResponse($this->directoriesService->updateAsyncValidations($this->provider));
    }
}
