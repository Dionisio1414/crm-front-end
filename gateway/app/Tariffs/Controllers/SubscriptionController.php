<?php

namespace App\Tariffs\Controllers;

use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponder;
use App\Tariffs\Repositories\SubscriptionRepository;
use App\Users\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    use ApiResponder;

    private $subscriptionRepository, $userRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository, UserRepository $userRepository)
    {
        //Repository
        $this->subscriptionRepository = $subscriptionRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @OA\Post(
     *      path="/tariffs/subscription/pay",
     *      tags={"Tariffs"},
     *      summary="Subscription Activate",
     *      @OA\RequestBody(
     *          description="Json Params",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                      @OA\Property(
     *                          property="provider",
     *                          example="trial/liqpay",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="tariff_id",
     *                          example=10,
     *                          type="int"
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

    public function pay(Request $request)
    {
        $subscription = $this->subscriptionRepository->createSubscription($request->data);

        if($subscription){
            return $this->successMessage($this->userRepository->setOnboardingTariff(true), Response::HTTP_OK);
        }
    }

    /**
     * @OA\Get(
     *      path="/tariffs/subscription/active",
     *      tags={"Tariffs"},
     *      summary="Subscription check Active",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Info Subscription",
     *                      @OA\Property(
     *                         property="id",
     *                         type="integer",
     *                         example=1,
     *                      ),
     *                      @OA\Property(
     *                         property="start_subscription",
     *                         example="2020-12-22",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="end_subscription",
     *                         example="2020-12-22",
     *                         type="string"
     *                      ),
     *                      @OA\Property(
     *                         property="type",
     *                         example="trial",
     *                         type="string"
     *                      ),
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

    public function active()
    {
        return $this->validResponse($this->subscriptionRepository->getActiveSubscription());
    }
}
