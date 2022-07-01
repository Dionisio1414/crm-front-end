<?php

namespace App\Tariffs\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use App\Languages\Repositories\LanguageRepositories;
use App\Tariffs\Repositories\CategoryTariffRepository;
use App\Tariffs\Repositories\TariffRepository;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;

class TariffController extends Controller
{
    use ApiResponder;

    private $tariffRepository, $categoryTariffRepository;

    public function __construct(TariffRepository $tariffRepository, CategoryTariffRepository $categoryTariffRepository)
    {
        //Repository
        $this->tariffRepository = $tariffRepository;
        $this->categoryTariffRepository = $categoryTariffRepository;
    }

    /**
     * @OA\Post(
     *      path="/tariffs/calculationTariffs",
     *      tags={"Tariffs"},
     *      summary="Tariffs calculation",
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  type="array",
     *                  property="steps",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="step_number",
     *                          type="integer",
     *                          example=1,
     *                      ),
     *                      @OA\Property(
     *                          property="items",
     *                          type="array",
     *                          @OA\Items(
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="integer",
     *                                  example=5,
     *                              ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=20,
     *                          ),
     *                      ),
     *                   ),
     *                 ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Tariffs and recommended",
     *                 type="array",
     *                 @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1,
     *                      ),
     *                      @OA\Property(
     *                         property="cost",
     *                         type="integer",
     *                         example=10000,
     *                      ),
     *                      @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Name",
     *                      ),
     *                      @OA\Property(
     *                         property="isRecommend",
     *                         type="boolean",
     *                         example=true,
     *                      ),
     *                      @OA\Property(
     *                         property="custom_columns",
     *                         title="Items",
     *                         type="array",
     *                         @OA\Items(
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="integer",
     *                                  example=1,
     *                              ),
     *                              @OA\Property(
     *                                  property="order",
     *                                  type="string",
     *                                  example="30",
     *                              ),
     *                              @OA\Property(
     *                                  property="title",
     *                                  type="string",
     *                                  example="Name",
     *                              ),
     *                              @OA\Property(
     *                                  property="active",
     *                                  type="int",
     *                                  example=1,
     *                              ),
     *                          )
     *                        )
     *                     )
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

    public function calculationTariffs(Request $request)
    {
        return $this->validResponse($this->tariffRepository->getTariffs($request->steps));
    }

    /**
     * @OA\Get(
     *      path="/tariffs",
     *      tags={"Tariffs"},
     *      summary="All Tariffs",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Tariffs",
     *                 type="array",
     *                 @OA\Items(
     *                      @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1,
     *                      ),
     *                      @OA\Property(
     *                         property="cost",
     *                         type="integer",
     *                         example=10000,
     *                      ),
     *                      @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="Name",
     *                      ),
     *                      @OA\Property(
     *                         property="custom_columns",
     *                         title="Items",
     *                         type="array",
     *                         @OA\Items(
     *                              @OA\Property(
     *                                  property="id",
     *                                  type="integer",
     *                                  example=1,
     *                              ),
     *                              @OA\Property(
     *                                  property="order",
     *                                  type="string",
     *                                  example="30",
     *                              ),
     *                              @OA\Property(
     *                                  property="title",
     *                                  type="string",
     *                                  example="Name",
     *                              ),
     *                              @OA\Property(
     *                                  property="active",
     *                                  type="int",
     *                                  example=1,
     *                              ),
     *                          )
     *                        )
     *                     )
     *                 )
     *              )
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

    public function index()
    {
        return $this->validResponse($this->tariffRepository->getTariffs());
    }

    public function detail()
    {
        return $this->validResponse($this->categoryTariffRepository->getCategoryTariffs());
    }
}
