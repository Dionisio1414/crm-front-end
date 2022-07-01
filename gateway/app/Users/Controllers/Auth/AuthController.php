<?php

namespace App\Users\Controllers\Auth;

use App\Core\Traits\FormatPhones;
use App\Core\Helpers\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Users\Controllers\Controller;
use App\Users\Repositories\UserRepository;
use App\Countries\Countries;
use App\Core\Traits\ValidateEmailPhone;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    use FormatPhones, ValidateEmailPhone;

    protected $userRepository, $countries;

	public function __construct(UserRepository $userRepository, Countries $countries)
	{
	    $this->userRepository = $userRepository;
        $this->countries = $countries;
        app()->setLocale(isset($request['data']['lang']) && $request['data']['lang'] ? $request['data']['lang'] : config('app.lang'));
        $this->middleware('guest')->except('logout');
	}

    /**
     * @OA\Post(
     *      path="/user/login",
     *      tags={"User"},
     *      summary="Login user into the system",
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
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="access_token",
     *                 title="Access Token",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="token_type",
     *                 title="Type Token",
     *                 example="Bearer",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="expires_at",
     *                 title="Expired Token",
     *                 example="2020-12-22",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="user",
     *                 title="User Info",
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

    public function login(Request $request)
    {
        $credentials = $request->data;

        if(isset($credentials['phone'])){
            /* second validate phone */
            $credentials = $this->validatePhone($request, $credentials, true);
            if(!isset($credentials['phone'])){
                return $this->errorResponse($credentials, Response::HTTP_OK);
            }
        }

        unset($credentials['lang']);

        if(Auth::guard('web')->once($credentials))
        {
            $user = Auth::guard('web')->user();

            $tokenResult = TokenService::_create($user);

            /* Update Auth Session */
            $this->userRepository->sessionTokenCreate($request, $tokenResult->token, $user);

            /* Delete Auth Flag */
            $this->userRepository->deleteUserAuthFlag($user->id);

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type'   => 'Bearer',
                'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'user'         => $user,
            ], 200);
        }

        return response()->json([
            'message' => 'Unauthorized user',
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/user/logout",
     *      tags={"User"},
     *      summary="Revoke token or Session",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     * )
     */

    public function logout(Request $request)
    {
        $this->userRepository->removeSession($request);
        $this->userRepository->logoutUser($request);

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
     * @OA\Post(
     *      path="/user/login/verify",
     *      tags={"User"},
     *      summary="Login user into the system by token email and phohe",
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
     *                          example="2456",
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
     *                      @OA\Property(
     *                          property="user_id",
     *                          title="user_id",
     *                          example=1,
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="id",
     *                          title="id",
     *                          example=1,
     *                          type="int"
     *                      ),
     *                      @OA\Property(
     *                          property="is_reset_password",
     *                          title="is_reset_password",
     *                          example=false,
     *                          type="boolean"
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
     *                 property="access_token",
     *                 title="Access Token",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="token_type",
     *                 title="Type Token",
     *                 example="Bearer",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="expires_at",
     *                 title="Expired Token",
     *                 example="2020-12-22",
     *                 type="string"
     *              ),
     *              @OA\Property(
     *                 property="user",
     *                 title="User Info",
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

    public function loginVerify(Request $request)
    {
        $credentials = $request->data;

        if (!isset($credentials['verification_token']) && !isset($credentials['user_id'])) {
            return response()->json([
                'message' => 'Verification token is required',
            ], 200);
        }

        $user = null;
        if (isset($credentials['email'])) {
            $user = $this->_checkVerificationToken($credentials['verification_token'], 'email', $credentials['email']);
        } else if (isset($credentials['phone'])) {
            /* second validate phone */
            $credentials = $this->validatePhone($request, $credentials, true);
            if(!isset($credentials['phone'])){
                return $this->errorResponse($credentials, Response::HTTP_OK);
            }

            $user = $this->_checkVerificationToken($credentials['verification_token'], 'phone', $credentials['phone']);
        } else if (isset($credentials['user_id'])) {
            $user = $this->userRepository->getUser($credentials['user_id']);
        } else if (isset($credentials['id'])) {
            /* auth by id and token (for invite user) */
            $user = $this->_checkVerificationToken($credentials['verification_token'], 'id', $credentials['id']);
        }

            /* Login */
            if ($user) {
                if (!$user->auth_flag || parse_url(request()->headers->get('referer'), PHP_URL_HOST) == config('app.main_domain')) {
                    Auth::guard('web')->loginUsingId($user->id);
                    $auth_user = Auth::guard('web')->user();

                    $tokenResult = TokenService::_create($auth_user);

                    if(parse_url(request()->headers->get('referer'), PHP_URL_HOST) == config('app.main_domain')){
                        /* Set Auth Flag */
                        $this->userRepository->deleteUserAuthFlag($user->id);
                    }else{
                        $this->userRepository->setUserAuthFlag($user->id);
                    }

                    //Check reset password
                    if(isset($credentials['is_reset_password']) && $credentials['is_reset_password']){
                        $this->userRepository->passwordReset(true, $user->id);
                    }

                    return response()->json([
                        'access_token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                        'user' => $user,
                    ], 200);
                }
            }

            return response()->json([
                'message' => 'Unauthorized user',
            ], 200);
    }

    public function _checkVerificationToken($token,$type,$credential)
    {
        $user = $this->userRepository->getUserByWhere($type, $credential);

        if($user) {
            if ($this->userRepository->checkVerificationToken($token, $user)) {
                return $user;
            }
        }

        return null;
    }
}
