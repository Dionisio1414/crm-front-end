<?php

namespace App\Services\Controllers\Directories;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\DirectoriesService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompaniesDepartmentsController extends Controller
{
    use ApiResponder;

    public $directoriesService, $provider, $provider_custom;

    /**
     * Create a new controller instance.
     *
     * @param DirectoriesService $directoriesService
     */
    public function __construct(DirectoriesService $directoriesService)
    {
        $this->directoriesService = $directoriesService;
        $this->provider = request()->segment(count(request()->segments()));
        $this->provider_custom = request()->segment(count(request()->segments())-1);
    }

    /**
     * @OA\Get(
     *      path="/directories/companies_departments",
     *      tags={"Directory - Companies Departments"},
     *      summary="Get Data Directories Companies Departments",
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
     *                 title="Get Data Directories Companies Departments",
     *                 @OA\Property(
     *                    property="list",
     *                    title="Get Data Directories Companies Departments",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              type="integer",
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              title="Title",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="is_default",
     *                              title="is_default",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="archive",
     *                              title="archive",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              title="00001",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="employees",
     *                              title="Employees",
     *                              type="array",
     *                              collectionFormat="multi",
     *                              @OA\Items(
     *                                  ref="#/components/schemas/DirectoriesCompaniesDepartmentsEmployee"
     *                              )
     *                          ),
     *                      )
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
        return $this->successResponse($this->directoriesService->getTable($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/directories/companies_departments",
     *      tags={"Directory - Companies Departments"},
     *      summary="Create Data",
     *      @OA\RequestBody(
     *          description="Create Data",
     *          required=true,
     *          @OA\JsonContent(
     *                          @OA\Property(
     *                              property="title",
     *                              title="Title",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="is_default",
     *                              title="is_default",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="employees",
     *                              title="Employees id",
     *                              type="array",
     *                              collectionFormat="multi",
     *                              @OA\Items(
     *                                  @OA\Property(
     *                                      property="employee_id",
     *                                      type="integer",
     *                                      example=1,
     *                                  ),
     *                                  @OA\Property(
     *                                      property="is_leader",
     *                                      type="boolean",
     *                                      example=false,
     *                                  ),
     *                              )
     *                          )
     *            )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data Directories",
     *                          @OA\Property(
     *                              property="title",
     *                              title="Title",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="is_default",
     *                              title="is_default",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="archive",
     *                              title="archive",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              title="00001",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="employees",
     *                              title="Employees",
     *                              type="array",
     *                              collectionFormat="multi",
     *                              @OA\Items(
     *                                  ref="#/components/schemas/DirectoriesCompaniesDepartmentsEmployee"
     *                              )
     *                          ),
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
     *      path="/directories/companies_departments/list/{id}",
     *      tags={"Directory - Companies Departments"},
     *      summary="Create Data",
     *      @OA\RequestBody(
     *          description="Create Data",
     *          required=true,
     *          @OA\JsonContent(
     *                          @OA\Property(
     *                              property="title",
     *                              title="Title",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="is_default",
     *                              title="is_default",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="employees",
     *                              title="Employees id",
     *                              type="array",
     *                              collectionFormat="multi",
     *                              @OA\Items(
     *                                  @OA\Property(
     *                                      property="employee_id",
     *                                      type="integer",
     *                                      example=1,
     *                                  ),
     *                                  @OA\Property(
     *                                      property="is_leader",
     *                                      type="boolean",
     *                                      example=false,
     *                                  ),
     *                              )
     *                          )
     *            )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data Directories",
     *                          @OA\Property(
     *                              property="title",
     *                              title="Title",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="is_default",
     *                              title="is_default",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="archive",
     *                              title="archive",
     *                              type="boolean",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              title="00001",
     *                              type="string",
     *                          ),
     *                          @OA\Property(
     *                              property="employees",
     *                              title="Employees",
     *                              type="array",
     *                              collectionFormat="multi",
     *                              @OA\Items(
     *                                  ref="#/components/schemas/DirectoriesCompaniesDepartmentsEmployee"
     *                              )
     *                          ),
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
     * @OA\Post(
     *      path="/directories/companies_departments/toArchive",
     *      tags={"Directory - Companies Departments"},
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
     *      path="/directories/companies_departments/store-async-validations",
     *      tags={"Directory - Companies Departments"},
     *      summary="Validate create Companies Departments",
     *      @OA\RequestBody(
     *          description="Change title",
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
        return $this->successResponse($this->directoriesService->storeAsyncValidations($this->provider_custom));
    }

    /**
     * @OA\Post(
     *      path="/directories/companies_departments/update-async-validations",
     *      tags={"Directory - Companies Departments"},
     *      summary="Update Companies Departments",
     *      @OA\RequestBody(
     *          description="Change title",
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
        return $this->successResponse($this->directoriesService->updateAsyncValidations($this->provider_custom));
    }
}
