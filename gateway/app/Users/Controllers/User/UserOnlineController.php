<?php

namespace App\Users\Controllers\User;

use App\Core\Http\Controllers\Controller;
use App\Events\User\UserOnline;
use App\Users\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Core\Traits\ApiResponder;
use App\Users\Repositories\UserRepository;

class UserOnlineController extends Controller
{
    use ApiResponder;

//    private $userRepository;
//
//    public function __construct(UserRepository $userRepository)
//    {
//        //Repository
//        $this->userRepository = $userRepository;
//    }

//    public function get()
//    {
//
//    }

    /**
     * @OA\Put(
     *      path="/user/{id}/online",
     *      tags={"User"},
     *      summary="Set user online",
     *     @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="socket_event",
     *                  example=".chat"
     *              ),
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

    public function __invoke($domain , User $user)
    {
        $user->status = User::ONLINE;
        $user->save();

        broadcast(new UserOnline($user));

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }


}
