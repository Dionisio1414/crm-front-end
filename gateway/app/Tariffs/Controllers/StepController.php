<?php

namespace App\Tariffs\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use App\Tariffs\Repositories\StepRepository;
use Illuminate\Http\JsonResponse;

class StepController extends Controller
{
    use ApiResponder;

    private $stepRepository;

    public function __construct(StepRepository $stepRepository)
    {
        //Repository
        $this->stepRepository = $stepRepository;
    }

    /**
     * @OA\Get(
     *      path="/tariffs/steps",
     *      tags={"Tariffs"},
     *      summary="Get Steps after registration",
     *      @OA\Parameter(
     *          name="lang",
     *          description="Language code",
     *          required=true,
     *          example="ru",
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Data Steps",
     *                 @OA\Items(
     *                      ref="#/components/schemas/Step"
     *                 )
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

    public function index(): JsonResponse
    {
        return $this->successResponse($this->stepRepository->getSteps());
    }
}
