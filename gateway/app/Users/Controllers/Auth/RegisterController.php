<?php


namespace App\Users\Controllers\Auth;

use App\Core\Helpers\CompanyCreator;
use App\Core\Helpers\EndResponse;
use App\Core\Traits\FormatPhones;
use App\Users\Repositories\UserRepository;
use TurboSMS;
use Illuminate\Http\Request;
use App\Users\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Core\Traits\ApiResponder;
use Illuminate\Validation\Rule;
use App\Countries\Countries;
use App\Core\Traits\SenderService;
use App\Core\Traits\ValidateEmailPhone;

class RegisterController extends Controller
{
    use ApiResponder, FormatPhones, SenderService, ValidateEmailPhone;

    protected $countries, $userRepository;

    public function __construct(Countries $countries, UserRepository $userRepository)
    {
        $this->middleware('guest')->except('logout');
        $request = request()->all();

        app()->setLocale(isset($request['data']['lang']) && $request['data']['lang'] ? $request['data']['lang'] : config('app.lang'));

        $this->countries = $countries;
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *      path="/user/register",
     *      tags={"User"},
     *      summary="Register user and creat Company",
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
     *                          example="+381111111111",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          title="password",
     *                          example="123456789",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
     *                          type="string"
     *                      ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Not Valid Data"
     *      ),
     * )
     */
    public function register(Request $request)
    {
        try {
            $dataAll = $request->all();
            $data['user'] = $dataAll['data'];

            /* Validate empty param login or phone */
            if($errorMessage = $this->isEmptyPhoneOrEmail($data['user'])){
                throw ValidationException::withMessages([
                    'param' => $errorMessage
                ]);
            }

            /* Validate email and phone */
            Validator::make($data['user'], $this->rules($data['user']))->validate();

            if(isset($data['user']['email']) && !isset($data['user']['password'])){
                $verification_token = $this->sendVerificationEmail($data['user']['email']);
            }else{
                /* second validate phone */
                if(isset($data['user']['phone'])){
                    $data['user'] = $this->validatePhone($request, $data['user'], true);
                    if(!isset($data['user']['phone'])){
                        throw ValidationException::withMessages([
                            'param' => $data['user']
                        ]);
                    }
                }

                if(!isset($data['user']['password'])){
                    Validator::make($data['user'], $this->rules($data['user']))->validate();
                    $verification_token = $this->sendVerificationPhone($data['user']['phone']);
                }
            }

            /* Create user */
            $data['user']['verification_token'] = $verification_token ?? null;
            $data['user']['password'] = isset($data['user']['password']) ? $data['user']['password'] : null;

            //$data['user']['password'] = isset($data['user']['password']) ? Hash::make($data['user']['password']) : null;

            $user_id = $this->userRepository->createUser($data['user']);
            $user = $this->userRepository->getUser($user_id);


            /* Return response and create a company */
            set_time_limit(0);

            if(isset($data['user']['from_admin'])) {
                EndResponse::respondOK(['user' => $user]);
            }else{
                EndResponse::respondOK(['success' => 'User registered successfully']);
            }

            if($user){
                CompanyCreator::_createCompany($user);
            }

        }catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }

    /**
     * @OA\Post(
     *      path="/user/resend/verification_token",
     *      tags={"User"},
     *      summary="Resend Email or Phone",
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
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="is_reset_password",
     *                          title="is_reset_password",
     *                          example="false",
     *                          type="boolean"
     *                      ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User email or phone resubmitted",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *       ),
     * )
     */

    public function resendVerificationToken(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validate empty param login or phone */
        if($errorMessage = $this->isEmptyPhoneOrEmail($data)){
            return $this->errorResponse($errorMessage, Response::HTTP_OK);
        }

        if(isset($data['email'])){
            $user = $this->userRepository->getUserByWhere('email', $data['email']);

            if($user){
                //Check reset password
                if(isset($data['is_reset_password']) && $data['is_reset_password']){
                    $this->userRepository->passwordReset(true, $user->id);
                }
            }
        }else{
            /* second validate phone */
            $data = $this->validatePhone($request,$data,true);
            if(!isset($data['phone'])){
                return $this->errorResponse($data, Response::HTTP_OK);
            }

            $user = $this->userRepository->getUserByWhere('phone', $data['phone']);

            if($user){
                $this->userRepository->deleteUserAuthFlag($user->id);
            }
        }

        if(optional($user)->id){
            if(isset($data['email'])) {
                $verification_token = $this->sendVerificationEmail($data['email']);
            }else{
                $verification_token = $this->sendVerificationPhone($data['phone']);
            }

            $this->userRepository->editUserById($user->id, ['verification_token'=>$verification_token]);

            return $this->successMessage('successful operation', Response::HTTP_OK);
        }

        return $this->errorResponse('User not found', Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/user/send/invite",
     *      tags={"User"},
     *      summary="Send invite to email",
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
     *                          property="invite_url",
     *                          title="invite_url",
     *                          example="url",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
     *                          type="string"
     *                      ),
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Invitation sent",
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found",
     *       ),
     * )
     */

    public function sendInvite(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];

        /* Validate empty param login or phone */
        if(!isset($data['email']) || !isset($data['invite_url'])){
            return $this->errorResponse(__('validation.custom.login.required'), Response::HTTP_OK);
        }

        $this->sendToEmail($data['email'], $data['invite_url']);

        return $this->successMessage('successful operation', Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *      path="/user/phone/validate",
     *      tags={"User"},
     *      summary="Validate mobile phone",
     *      security={},
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="phone",
     *                          title="phone",
     *                          example="+380500000000",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
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
     *                 property="code",
     *                 title="UA",
     *                 example="UA",
     *              ),
     *              @OA\Property(
     *                 property="phone",
     *                 title="phone",
     *                 example="+380500000000",
     *              ),
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     * )
     */

    public function validPhone(Request $request)
    {
        $dataAll = $request->all();
        $data = $dataAll['data'];



        /* Validate empty param login or phone */
        if(!isset($data['phone']) || !$data['phone']){
            return $this->successMessage(false, Response::HTTP_OK);
        }

        $data = $this->validatePhoneData($request, $data);

        if (!isset($data['phone'])) {
            return $this->successMessage(false, Response::HTTP_OK);
        } else {
            return $this->successResponse(json_encode($data), Response::HTTP_CREATED);
        }
    }
}
