<?php

namespace App\Services\Controllers\Reports;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\Service\ReportsService;

class WorkTimeController extends Controller
{
    use ApiResponder;

    public $reportsService, $provider;

    /**
     * Create a new controller instance.
     *
     * @param ReportsService $reportsService
     */
    public function __construct(ReportsService $reportsService)
    {
        $this->reportsService = $reportsService;
        $this->provider = request()->segment(count(request()->segments()));
    }

    /**
     * @OA\Get(
     *      path="/reports/work_time",
     *      tags={"Reports"},
     *      summary="Get Data Report",
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

    public function index(): JsonResponse
    {
        return $this->successResponse($this->reportsService->get($this->provider));
    }
}


