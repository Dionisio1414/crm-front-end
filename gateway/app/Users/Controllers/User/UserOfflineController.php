<?php

namespace App\Users\Controllers\User;

use App\Core\Http\Controllers\Controller;
use App\Events\User\UserOffline;
use App\Users\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Core\Traits\ApiResponder;
use App\Users\Repositories\UserRepository;

class UserOfflineController extends Controller
{
    use ApiResponder;

//    private $userRepository;

//    public function __construct(UserRepository $userRepository)
//    {
//        //Repository
//        $this->userRepository = $userRepository;
//    }

    /**
     * @OA\Put(
     *      path="/user/{id}/offline",
     *      tags={"User"},
     *      summary="Set user offline",
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
        $user->status = User::OFFLINE;
        $user->save();

        broadcast(new UserOffline($user));

        return $this->successMessage('Successful operation', Response::HTTP_OK);

    }
}
