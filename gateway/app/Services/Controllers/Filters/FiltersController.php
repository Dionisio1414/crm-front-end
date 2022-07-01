<?php

namespace App\Services\Controllers\Filters;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\DirectoriesService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Service\FiltersService;

class FiltersController extends Controller
{
    use ApiResponder;

    public $filtersService, $provider;

    /**
     * Create a new controller instance.
     *
     * @param FiltersService $directoriesService
     */
    public function __construct(FiltersService $filtersService)
    {
        $this->filtersService = $filtersService;
        $this->provider = request()->segment(count(request()->segments()));
    }

    /**
     * @OA\Get(
     *      path="/filters/{filter}",
     *      tags={"Filters"},
     *      summary="Get Data Filters",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  title="Get Data Filters",
     *                  @OA\Property(
     *                    property="selects",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="periods",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="lists",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="booleans",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="ranges",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="is_active_filter",
     *                    title="Active Filter",
     *                    type="boolean",
     *                  ),
     *
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
        return $this->successResponse($this->filtersService->get($this->provider));
    }

    /**
     * @OA\Post(
     *      path="/filters/{filter}",
     *      tags={"Filters"},
     *      summary="Create/Delete Filter",
     *      @OA\RequestBody(
     *          description="Create/Delete Filter",
     *          required=true,
     *     @OA\JsonContent(
     *                  @OA\Property(
     *                    property="selects",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="periods",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="lists",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="booleans",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="ranges",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="is_active_filter",
     *                    title="Active Filter",
     *                    type="boolean",
     *                  ),
     *     )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                  title="Get Data Filters",
     *                  @OA\Property(
     *                    property="selects",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="periods",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="lists",
     *                    title="Objects or array data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="booleans",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="ranges",
     *                    title="Objects data filters",
     *                    type="object",
     *                    @OA\Items()
     *                  ),
     *                  @OA\Property(
     *                    property="is_active_filter",
     *                    title="Active Filter",
     *                    type="boolean",
     *                  ),
     *
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
        return $this->successResponse($this->filtersService->store($this->provider));
    }
}


