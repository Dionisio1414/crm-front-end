<?php

namespace App\Users\Controllers\User;

use App\Core\Traits\FormatPhones;
use App\Users\Controllers\Auth\SocialController;
use App\Users\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;
use App\Users\Repositories\CompanyRepository;
use App\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Countries\Countries;
use App\Services\Service\DirectoriesService;
use App\Core\Traits\SenderService;
use App\Core\Traits\ValidateEmailPhone;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use ApiResponder, FormatPhones, SenderService, ValidateEmailPhone;

    private $userRepository, $companyRepository;
    protected $countries;
    protected $request;
    public $directoriesService;

    public function __construct(UserRepository $userRepository,
                                CompanyRepository $companyRepository,
                                Countries $countries,
                                DirectoriesService $directoriesService
    )
    {
        $this->companyRepository = $companyRepository;
        $this->userRepository = $userRepository;
        $this->countries = $countries;

        /* Services products */
        $this->directoriesService = $directoriesService;

        app()->setLocale(isset(request()['lang']) ? request()['lang'] : config('app.lang'));
    }

     /**
     * @OA\Get(
     *      path="/user/auth",
     *      tags={"User"},
     *      summary="Get User",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
      *              @OA\Property(
      *                 property="data",
      *                 title="User",
      *                 ref="#/components/schemas/User"
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

    public function index()
    {
        return $this->validResponse(
            $this->userRepository->getAuthUser(),
            Response::HTTP_OK
        );
    }

    /**
     * @OA\Post(
     *      path="/user/password/change",
     *      tags={"User"},
     *      summary="Change Password",
     *      @OA\RequestBody(
     *          description="Change passwords User",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                      @OA\Property(
     *                          property="password",
     *                          type="string",
     *                          example="123l456",
     *                      ),
     *              ),
     *          ),
     *      ),
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

    public function changePassword(Request $request)
    {
        $rules = [
            'password' => [
                'required', 'min:8', 'string', 'regex:/[0-9]/', 'regex:/[a-zA-Z]/', 'regex:/^[^а-яА-Я]+$/'
            ]
        ];

        $validator = Validator::make($request->data, $rules);

        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_BAD_REQUEST);
        }

        $response = $this->userRepository->changePassword($request->data['password']);

        $this->userRepository->deleteVerificationToken();

        return $this->successMessage($response, Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/user/password/check_old_password",
     *      tags={"User"},
     *      summary="Check Old Password",
     *      @OA\RequestBody(
     *          description="Check Old Password",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                      @OA\Property(
     *                          property="password",
     *                          type="string",
     *                          example="123l456",
     *                      ),
     *              ),
     *          ),
     *      ),
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

    public function checkOldPassword(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('Bad Request', Response::HTTP_BAD_REQUEST);
        }

        return $this->successMessage($this->userRepository->checkOldPassword($request->data['password']), Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/user/authorization/send_token",
     *      tags={"User"},
     *      summary="Change Email or Phone",
     *      security={},
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="email",
     *                          title="email",
     *                          example="test@example.com",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          title="phone",
     *                          example="380501111111",
     *                          type="string"
     *                      )
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User email or phone changes",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *       ),
     * )
     */

    public function sendToken(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validate empty param login or phone */
        if($errorMessage = $this->isEmptyPhoneOrEmail($data)){
            return $this->errorResponse($errorMessage, Response::HTTP_OK);
        }

        $user = $this->userRepository->getAuthUser();

        /* Validate email and phone */
        $validator = $this->validatePhoneOrEmail($data, $user->id);
        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_OK);
        }

        if(isset($data['email'])){
            $verification_token = $this->sendToEmailPin($data['email']);
        }else{

            /* second validate phone */
            $data = $this->validatePhone($request,$data);
            if(!isset($data['phone'])){
                return $this->errorResponse($data, Response::HTTP_OK);
            }

            $verification_token = $this->sendVerificationPhone($data['phone']);
        }

        $this->userRepository->editUser(['verification_token'=>$verification_token]);

        return $this->successMessage('successful operation', Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/user/authorization/change",
     *      tags={"User"},
     *      summary="Check verifiction token and change Email or Phone",
     *      security={},
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="verification_token",
     *                          title="verification_token",
     *                          example="1234",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          title="email",
     *                          example="test@example.com",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          title="phone",
     *                          example="+380501111111",
     *                          type="string"
     *                      ),
     *              ),
     *          ),
     *      ),
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
     *          description="Bad Request",
     *       ),
     * )
     */

    public function changeAutorization(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validate empty param login or phone */
        if($errorMessage = $this->isEmptyPhoneOrEmail($data)){
            return $this->errorResponse($errorMessage, Response::HTTP_OK);
        }

        $user = $this->userRepository->getAuthUser();

        /* Validate email and phone */
        $validator = $this->validatePhoneOrEmail($data, $user->id);
        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_OK);
        }

        $checkVerification = $this->userRepository->checkVerificationToken($data['verification_token'] ?? null);

        if($checkVerification){

            if(isset($data['phone'])){
                /* second validate phone */
                $data = $this->validatePhone($request,$data);
                if(!isset($data['phone'])){
                    return $this->errorResponse($data, Response::HTTP_OK);
                }
            }

            unset($data['verification_token']);

            $this->userRepository->editUser($data);
        }

        return $this->successMessage($checkVerification, Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="/user/authorization/change/social",
     *      tags={"User"},
     *      summary="Update or Create social autorization",
     *      security={},
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="email",
     *                          title="email",
     *                          example="test@test.com",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="provider",
     *                          title="provider",
     *                          type="string",
     *                          example="facebook/google/sign-in-with-apple"
     *                      ),
     *              ),
     *          ),
     *      ),
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
     *          description="Bad Request",
     *       ),
     * )
     */

    public function changeAutorizationSocial(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validation */
        if(!isset($data['email'])){
            return $this->errorResponse(__('validation.custom.login.required'),Response::HTTP_OK);
        }

        if(!in_array($data['provider'], SocialController::$providers)){
            return $this->errorResponse(__('validation.custom.login_social.provider'),Response::HTTP_OK);
        }

        $rules = [
            'email' => isset($data['email']) ? ['required', 'string', 'email', 'max:255'] : [],
        ];

        $validator = Validator::make($data, $rules);
        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_OK);
        }

        $user = $this->userRepository->getAuthUser();
        $social['social'][] = [
            'provider' => $data['provider'],
            'email'    => $data['email']
        ];

        /* Set social */
        if(!$user->social){
            $rezult = $this->userRepository->updateSocial($social);
        }else{
            $checkUpdate = false;
            $newData = $user->social;
            foreach ($newData as $item){
                if($item->provider == $data['provider']){
                    $item->email = $data['email'];
                    $checkUpdate = true;
                }
            }
            if(!$checkUpdate){
                $newData[] = $social['social'][0];
            }

            $rezult = $this->userRepository->updateSocial(['social'=>$newData]);
        }

        return $this->successMessage($rezult, Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="/user/authorization/delete/social",
     *      tags={"User"},
     *      summary="Delete social autorization",
     *      security={},
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="provider",
     *                          title="provider",
     *                          type="string",
     *                          example="facebook/google/sign-in-with-apple"
     *                      ),
     *              ),
     *          ),
     *      ),
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
     *          description="Bad Request",
     *       ),
     * )
     */

    public function deleteAutorizationSocial(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validation */

        if(!in_array($data['provider'], SocialController::$providers)){
            return $this->errorResponse(__('validation.custom.login_social.provider'),Response::HTTP_OK);
        }

        $user = $this->userRepository->getAuthUser();

        /* Set social */
        if($newData = $user->social){
            $removeKey = -1;
            foreach ($newData as $key=>$item){
                if($item->provider == $data['provider']){
                    $removeKey = $key;
                }
            }

            if($removeKey >= 0){
                unset($newData[$removeKey]);
                sort($newData);
                if(count($newData) <= 0){
                    $newData = null;
                }
                $rezult = $this->userRepository->updateSocial(['social'=>$newData]);
            }else{
                $rezult = false;
            }
        }else{
            return $this->errorResponse('Not social',Response::HTTP_OK);
        }

        return $this->successMessage($rezult, Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *      path="/user/auth",
     *      tags={"User"},
     *      summary="Change User and Company",
     *      @OA\RequestBody(
     *          description="Change User and Company User",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="data",
     *                      @OA\Property(
     *                          property="first_name",
     *                          type="string",
     *                          example="First Name",
     *                      ),
     *                      @OA\Property(
     *                          property="last_name",
     *                          type="string",
     *                          example="Last Name",
     *                      ),
     *                      @OA\Property(
     *                          property="middle_name",
     *                          type="string",
     *                          example="Middle Name",
     *                      ),
     *                      @OA\Property(
     *                          property="phone",
     *                          type="int",
     *                          example=380501111111,
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string",
     *                          example="test@example.com",
     *                      ),
     *                      @OA\Property(
     *                          property="company_name",
     *                          type="string",
     *                          example="Test company",
     *                      ),
     *                      @OA\Property(
     *                          property="domain",
     *                          type="string",
     *                          example="test.gateway.esl.ua",
     *                      ),
     *                      @OA\Property(
     *                          property="position_id",
     *                          type="int",
     *                          example=2,
     *                      ),
     *                      @OA\Property(
     *                          property="language_interface_id",
     *                          type="int",
     *                          example=1,
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
     *                 ref="#/components/schemas/User"
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
        $user = $this->userRepository->getAuthUser();

        if(isset($data['phone'])){
            try{
                $data['phone'] = $this->makeFormat($request, $data['phone']);
            }catch(\Exception $ex){
                $messages = $ex->getMessage();
                $data['phone'] = null;
            }

            if(!$data['phone']){
                return $this->errorResponse(['phone'=>[__('validation.phone')]], Response::HTTP_OK);
            }
        }

        $validator = $data ? Validator::make($data, $this->rulesAuth($user->id, $data, isset($data['auth_email']) || isset($data['auth_phone']) ? true : false)) : null;

        if(!$validator){
            return $this->errorResponse('Bad Request', Response::HTTP_OK);
        }

        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->toArray(), Response::HTTP_OK);
        }

        // Edit User Info
        $this->userRepository->setOnboardingEdit(true);

        /* Remove auth data, if exists */
        $authData = $this->_unsetAuthData($data);

        $this->userRepository->editUser($authData['auth']);
        $data = $authData['data'];

        /* Return if not owner */
        if(!$user->detail->is_owner){
            $this->userRepository->setOnboardingTariff(true);

            /* Set invited user */
            $this->_updateEmployeeUser($user->id);

            return $this->validResponse(
                $this->userRepository->getUser($user->id),
                Response::HTTP_OK
            );
        }

        /* If owner and not change company  */
        if(!isset($data['company_name'])){
            return $this->validResponse(
                $this->userRepository->getUser($user->id),
                Response::HTTP_OK
            );
        }

        /* If owner and change company  */
        /* Edit Company Info */
        $data['name'] = $data['company_name'];

        /* Update Company */
        $company = $this->companyRepository->generateCompany($user->company->id, $data, $request);
        $dataCompany = [
            'language_interface_id'  => 1,
            'currency_id'  => 1,
        ];
        $company->update($dataCompany);

        /* Generate organizations */
        $this->_generateOrganization($data);

        /* Generate employees and User */
        $this->_generateEmployeeUser($data);

        return $this->validResponse(
            $this->userRepository->getUser($user->id),
            Response::HTTP_OK
        );
    }

    protected function _generateOrganization($data)
    {
        $full_name = $data['last_name'] . ' ' . $data['first_name'] . ' ' . $data['middle_name'];

        $rezult = [
            'main' => [
                'first_name'   => isset($data['first_name']) ? $data['first_name'] : null,
                'last_name'    => isset($data['last_name']) ? $data['last_name'] : null,
                'middle_name'  => isset($data['middle_name']) ? $data['middle_name'] : null,
                'position_id'  => isset($data['position_id']) ? $data['position_id'] : null,
                'organization_type_id' => 3,
                'title' => $full_name,
            ],
            'contact' => [
                'values' => [
                    [
                        'phone'=>isset($data['phone']) ? $data['phone'] : null
                    ],
                    [
                        'email'=>isset($data['email']) ? $data['email'] : null,
                    ],
                ]
            ],
            'is_default'  => true,
            'title' => $full_name,
        ];

        $this->directoriesService->createItems('organizations', $rezult);
    }

    protected function _generateEmployeeUser($data)
    {
        $rezult = [
            'first_name'   => isset($data['first_name']) ? $data['first_name'] : null,
            'last_name'    => isset($data['last_name']) ? $data['last_name'] : null,
            'middle_name'  => isset($data['middle_name']) ? $data['middle_name'] : null,
            'position_id'  => isset($data['position_id']) ? $data['position_id'] : null,
            'is_user'      => true,
            'is_employee'  => true,
            'company_department_id' => 4, /* ID administration company */
            'contact' => [
                'mobile_phone'=>isset($data['phone']) ? $data['phone'] : null,
                'email'=>isset($data['email']) ? $data['email'] : null
            ],
            'is_step' => true
        ];

        $this->directoriesService->createItems('employees', $rezult);
    }

    protected function _updateEmployeeUser($id)
    {
        $rezult = [
            'is_invited' => true,
            'by_gateway_user_id' => $id
        ];

        $this->directoriesService->editItems('users', $id, $rezult);
    }

    public function createRegular(Request $request)
    {
        $data = $request->all();

        if(!isset($data['user_id']) || !$owner = $this->userRepository->getUser($data['user_id'])){
            return $this->errorResponse('Not Found User', Response::HTTP_OK);
        }

        $data['company_id'] = $owner->company_id;
        $data['verification_token'] = Str::random(15);

        $user_id = $this->userRepository->createUser($data);

        $data['detail']['is_owner'] = false;
        $this->userRepository->editUserById($user_id, $data);

        return $this->validResponse(
            $this->userRepository->getUser($user_id)->setAppends(['invite_url']),
            Response::HTTP_OK
        );
    }

    public function updateRegular(Request $request, $id)
    {
        $data = $request->all();

        if(!isset($data['user_id']) || !$owner = $this->userRepository->getUser($data['user_id'])){
            return $this->errorResponse('Not Creator Found User', Response::HTTP_OK);
        }

        $this->userRepository->editUserById($id,$data);

        return $this->validResponse(
            $this->userRepository->getUser($id),
            Response::HTTP_OK
        );
    }

    public function _unsetAuthData($data)
    {
        $authData = $data;
        if(isset($authData['auth_email']) && isset($authData['auth_phone'])){
            unset($authData['email'], $authData['phone']);
            unset($data['auth_email'],$authData['auth_phone']);
        }else if(isset($authData['auth_email'])){
            unset($authData['phone'], $authData['auth_email']);
            unset($data['auth_email']);
        }else if(isset($authData['auth_phone'])){
            unset($authData['email'], $authData['auth_phone']);
            unset($data['auth_phone']);
        }

        return [
            'auth' => $authData,
            'data' => $data
        ];
    }

    //validation

    /**
     * @OA\Post(
     *      path="/user/store-async-validations",
     *      tags={"User"},
     *      summary="Validate create User",
     *      @OA\RequestBody(
     *          description="Change phone,email,name,domain",
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

    public function storeAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $data = $request->get('validate');

            $table = 'users';
            $table_company = 'companies';
            switch ($key) {
                case 'company_name':
                    $data['name'] = $data['company_name'];
                    unset($data['company_name']);
                    $table = $table_company;
                    break;
            }

            // Format Phone
            if($key == 'phone'){
                /* second validate phone */
                $data = $this->validatePhone($request, $data);
                if(!$data['phone']){
                    return $this->errorResponse(['phone'=>[$data]], Response::HTTP_OK);
                }

            }else if($key == 'domain'){
                $table = $table_company;

                if(strpos($data['domain'], env('DOMAIN_URL')) === false
                    && trim($data['domain']) ==  '.' . env('DOMAIN_URL')
                    && trim($data['domain']) == env('DOMAIN_URL')
                    && !$data['domain']
                    && trim($data['domain']) == '')
                {
                    return $this->errorResponse('Not Validate Domain', Response::HTTP_OK);
                }
            }

            $validator = Validator::make($data,
                [
                    $key => 'unique:' . $table . ',' . $key . '|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }

    /**
     * @OA\Post(
     *      path="/user/update-async-validations",
     *      tags={"User"},
     *      summary="Update Updated User",
     *      @OA\RequestBody(
     *          description="Change phone,email,name,domain",
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

    public function updateAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $data = $request->get('validate');
            $domain_rule = '';

            $table = 'users';
            $table_company = 'companies';
            switch ($key) {
                case 'company_name':
                    $data['name'] = $data['company_name'];
                    unset($data['company_name']);
                    $table = $table_company;
                    break;
            }

            // Format Phone
            if($key == 'phone'){
                /* second validate phone */
                $data = $this->validatePhone($request, $data, $request->is_step ?? false);
                if(!$data['phone']){
                    return $this->errorResponse(['phone'=>[$data]], Response::HTTP_OK);
                }
            }else if($key == 'domain'){
                $table = $table_company;

                if(strpos($data['domain'], env('DOMAIN_URL')) === false
                    && trim($data['domain']) ==  '.' . env('DOMAIN_URL')
                    && trim($data['domain']) == env('DOMAIN_URL')
                    && !$data['domain']
                    && trim($data['domain']) == '')
                {
                    return $this->errorResponse('Not Validate Domain', Response::HTTP_OK);
                }

                $domain_rule = '|regex:/^[a-z-.0-9]+$/';
            }

            $id = $request->id;
            if($table == 'companies'){
                if($request->id){
                    $user = $this->userRepository->getUser($request->id);
                    if(optional($user->company)->id){
                        $id = $user->company->id;
                    }
                }
            }

            $validator = Validator::make($data,
                [
                    $key => 'unique:' . $table . ',' . $key . ',' . $id . 'id|max:255' . $domain_rule,
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }
}
