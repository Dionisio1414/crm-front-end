<?php

namespace App\Users\Controllers\Auth;

use App\Core\Helpers\CompanyCreator;
use App\Core\Helpers\EndResponse;
use App\Core\Helpers\TokenService;
use App\Users\Controllers\Controller;
use App\Users\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\Core\Traits\ApiResponder;
use App\Users\Repositories\UserRepository;

class  SocialController extends Controller
{
    use ApiResponder;

    public static $providers = ['facebook', 'google' , 'apple-id'];

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        app()->setLocale(isset($request['data']['lang']) && $request['data']['lang'] ? $request['data']['lang'] : config('app.lang'));

        $this->middleware(['guest','cors'])->except('logout');
    }

    /**
     * @OA\Post(
     *      path="/user/register/social",
     *      tags={"User"},
     *      summary="Register and Login User Company with Social",
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
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="provider",
     *                          title="provider",
     *                          type="string",
     *                          example="facebook/google/sign-in-with-apple"
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
     *                          type="string"
     *                      ),
     *                  ),
     *              ),
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
     *      @OA\Response(
     *          response=402,
     *          description="Missing email"
     *      ),
     * )
     */
    public function redirectToProvider(Request $request)
    {
        try {
            return Socialite::driver($request->provider)->stateless()->redirect();
        }
        catch(\Exception $ex) {
            return $this->errorResponse('Bad Request', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Obtain the user information from Social.
     */
    public function handleProviderCallback(Request $request)
    {

        try {
            $response = $request->data;

            /* Validation */
            if(!isset($response['email'])){
                throw ValidationException::withMessages([
                    'param' => __('validation.custom.login.required')
                ]);
            }

            if(!in_array($response['provider'], self::$providers)){
                throw ValidationException::withMessages([
                    'param' => __('validation.custom.login_social.provider')
                ]);
            }

            $rules = [
                'email' => isset($response['email']) ? ['required', 'string', 'email', 'max:255'] : [],
            ];

            Validator::make($response, $rules)->validate();

            //check register account by social
            $checkUser = $this->userRepository->getUserBySocialEmail($response['email'], $response['provider']);
            if($checkUser){
                return $this->_autorization($request, $checkUser);
            }

            $data['social'][] = (object)[
                'provider' => $response['provider'],
                'email'    => $response['email']
            ];

            /* Create user */
            $user_id = $this->userRepository->createUser($data);
            $checkUser = $this->userRepository->getUser($user_id);

            $successData = $this->_autorization($request, $checkUser);

            if($successData['user']){
                set_time_limit(0);
                CompanyCreator::_createCompany($successData['user'], $successData);
            }

        }catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }

    /**
     * @OA\Post(
     *      path="/user/login/social",
     *      tags={"User"},
     *      summary="Login User Company with Social",
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
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="provider",
     *                          title="provider",
     *                          type="string",
     *                          example="facebook/google/sign-in-with-apple"
     *                      ),
     *                      @OA\Property(
     *                          property="lang",
     *                          title="lang",
     *                          example="ua",
     *                          type="string"
     *                      ),
     *                  ),
     *              ),
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
     *      @OA\Response(
     *          response=402,
     *          description="Missing email"
     *      ),
     * )
     */
    public function login(Request $request)
    {
        try {
            $response = $request->data;

            if(!isset($response['email']) || !isset($response['provider'])){
                throw ValidationException::withMessages([
                    'param' => __('validation.custom.login.required')
                ]);
            }

            $rules = [
                'email' => isset($response['email']) ? ['required', 'string', 'email', 'max:255'] : [],
            ];

            Validator::make($response, $rules)->validate();

            //check register account by social
            $checkUser = $this->userRepository->getUserBySocialEmail($response['email'], $response['provider']);
            if($checkUser){
                return $this->_autorization($request, $checkUser);
            }

            return response()->json([
                'message' => 'Unauthorized user',
            ], 200);

        }catch (ValidationException $e) {
            return $this->errorResponse($e->errors(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_OK);
        }
    }

    public function _authUser($checkUser)
    {
        Auth::guard('web')->loginUsingId($checkUser->id);
        $user = Auth::guard('web')->user();

        /* Delete Auth Flag */
        $this->userRepository->deleteUserAuthFlag($checkUser->id);

        return $user;
    }

    public function _autorization($request, $checkUser)
    {
        $user = $this->_authUser($checkUser);
        $tokenResult = TokenService::_create($user);
        $this->userRepository->sessionTokenCreate($request, $tokenResult->token, $user);

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'user'         => $user,
        ];
    }
}
