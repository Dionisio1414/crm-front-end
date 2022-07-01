<?php

namespace App\Tariffs\Repositories;

use App\Tariffs\Model\Subscription as Model;
use App\Core\Repositories\CoreRepository;
use Illuminate\Support\Carbon;
use App\Core\Model\Yesno;
use Illuminate\Support\Facades\Auth;

/**
 * Class CharacteristicRepository.
 */
class SubscriptionRepository extends CoreRepository
{

    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getSubscriptions()
    {
        return $this->model()->get();
    }

    public function createSubscription($data)
    {
        $checkSubscription = $this->getActiveSubscription();
        if($checkSubscription){
            $this->closeSubscription($checkSubscription->id);
        }
        $subscription = [
            'type' => $data['provider'],
            'tariff_id' => $data['tariff_id'],
            'user_id' => Auth::user()->id,
            'start_subscription' => Carbon::now(),
            'end_subscription'   => Carbon::now()->addMonth(1),
            'status'   => Yesno::YES,
        ];
        return $this->model()->create($subscription);
    }

    public function getActiveSubscription()
    {
        return $this->model()->where('user_id', Auth::user()->id)
                             ->where('status', Yesno::YES)
                             ->first(['id', 'start_subscription', 'end_subscription', 'type']);
    }

    public function closeSubscription()
    {
        $this->model()->where('user_id', Auth::user()->id)
            ->where('status', Yesno::YES)
            ->update(['status'=>Yesno::NO]);
    }
}
