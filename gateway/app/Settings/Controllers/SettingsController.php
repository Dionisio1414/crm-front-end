<?php

namespace App\Settings\Controllers;

use App\Core\Helpers\CompanyCreator;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;
use App\Users\Repositories\CompanyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    use ApiResponder;

    private $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        //Repository
        $this->companyRepository = $companyRepository;
    }

    /**
     * @OA\Get(
     *      path="/settings/main",
     *      tags={"Settings"},
     *      summary="Get Main Settings",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Main Settings",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Company"
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

    public function index(Request $request)
    {
        return $this->validResponse($this->companyRepository->getCompany(Auth::user()->company->id));
    }

    /**
     * @OA\Post(
     *      path="/settings/main",
     *      tags={"Settings"},
     *      summary="Change Settings Data",
     *      @OA\RequestBody(
     *          description="Change Settings Data",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string",
     *                          example="Company Name",
     *                      ),
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                          example="Domain",
     *                      ),
     *                      @OA\Property(
     *                          property="language_interface_id",
     *                          type="int",
     *                          example=1,
     *                      ),
     *                      @OA\Property(
     *                          property="currency_id",
     *                          type="int",
     *                          example=2,
     *                      ),
     *                      @OA\Property(
     *                          property="data_prohibition",
     *                          example="2020-09-18 11:42:35",
     *                          type="timestamp"
     *                      ),
     *                      @OA\Property(
     *                          property="archive_cleare",
     *                          type="int",
     *                          example=22,
     *                      ),
     *                      @OA\Property(
     *                          property="is_сhange_history",
     *                          type="boolean",
     *                          example=true,
     *                      ),
     *                      @OA\Property(
     *                          property="is_residue_control",
     *                          type="boolean",
     *                          example=true,
     *                      ),
     *                      @OA\Property(
     *                          property="is_labels_price_tags",
     *                          type="boolean",
     *                          example=true,
     *                      ),
     *                      @OA\Property(
     *                          property="is_kits",
     *                          type="boolean",
     *                          example=true,
     *                      ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="User",
     *                 ref="#/components/schemas/Company"
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

    public function store(Request $request)
    {
        $data = $request->data;
        $validator = $this->companyRepository->validate($data);

        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_BAD_REQUEST);
        }

        return $this->validResponse(
            $this->companyRepository->generateCompany(Auth::user()->company->id, $data, $request, Auth::user()),
            Response::HTTP_OK
        );
    }
}
