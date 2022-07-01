<?php

namespace App\Users\Controllers\User;

use App\Core\Helpers\CompanyCreator;
use App\Core\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Core\Traits\ApiResponder;
use Illuminate\Support\Facades\Validator;
use App\Users\Repositories\CompanyRepository;
use App\Users\Model\User\Company;

class CompanyController extends Controller
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
     *      path="/user/company/check_generate",
     *      tags={"User"},
     *      summary="Check generate company",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="success",
     *                 title=true,
     *                 type="boolean",
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

    public function checkGenerate(Request $request)
    {
        return $this->successMessage(
            optional(Auth::user()->company)->db_database ? true : false,
            Response::HTTP_OK
        );
    }

    public function index(Request $request, $id=null)
    {
        return $this->validResponse(
            $id ? $this->companyRepository->getCompany($id) : Auth::user()->company,
            Response::HTTP_OK
        );
    }

    public function update(Request $request, $id=null)
    {
        $data = $request->all();
        $validator = $this->companyRepository->validate($data);

        if ($validator->fails()) {
            return $this->errorResponse('Error', Response::HTTP_BAD_REQUEST);
        }

        if(!$id){
            $id = Auth::user()->company->id;
        }

        return $this->validResponse(
            $this->companyRepository->generateCompany($id, $data),
            Response::HTTP_OK
        );
    }

    public function destroy($id)
    {
        $company = $this->companyRepository->getCompany($id);
        return $this->successMessage(CompanyCreator::_removeCompany($company), Response::HTTP_OK);
    }
}
