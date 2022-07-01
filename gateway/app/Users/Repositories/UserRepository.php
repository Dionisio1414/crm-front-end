<?php

namespace App\Users\Repositories;

use App\Core\Helpers\TokenService;
use App\Core\Model\Yesno;
use App\Users\Model\User as Model;
use App\Core\Repositories\CoreRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Users\Model\User\SessionUser;

/**
 * Class CharacteristicRepository.
 */
class UserRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getAuthUser()
    {
        return Auth::user();
    }

    public function getUser($id)
    {
        return $this->model()->find($id);
    }

    public function setOnboardingTariff($status = true)
    {
        $active = $this->getAuthUser()->detail()->update([
            'onboarding_tariff' => $status,
        ]);

        return $active ? true : false;
    }

    public function setOnboardingEdit($status = true)
    {
        $active = $this->getAuthUser()->detail()->update([
            'onboarding_edit' => $status,
        ]);

        return $active ? true : false;
    }

    public function deleteVerificationToken()
    {
        $this->getAuthUser()->update([
            'verification_token' => null
        ]);
    }

    public function passwordReset($status = false, $user_id = null)
    {
            if(!$user_id){
                $this->getAuthUser()->detail()->update([
                    'password_reset' => $status,
                ]);
            }else{
                $user = $this->getUser($user_id);
                if($user){
                    $user->detail()->update(['password_reset'=>$status]);
                }
            }
    }

    public function changePassword($password)
    {
        $this->passwordReset(false, $this->getAuthUser()->id);

        return $this->getAuthUser()->update([
            'password' => Hash::make($password),
        ]);
    }

    public function checkOldPassword($password)
    {
        return Hash::check($password, optional($this->getAuthUser())->password);
    }

    public function checkVerificationToken($verification_token, $user = null)
    {
        if($user){
            return $user->verification_token == $verification_token;
        }

        return optional($this->getAuthUser())->verification_token == $verification_token;
    }

    public function editUser($data)
    {
        return $this->getAuthUser()->update($data);
    }

    public function updateSocial($data)
    {
        return $this->getAuthUser()->update($data);
    }

    public function editUserById($id, $data)
    {
        $user =  $this->getUser($id);

        if(isset($data['session'])){

            $session = $this->searchSession($user,$data['session']);

            if(!$session){
                $user->session()->create($data['session']);
            }else if(!$token = $this->searchToken($user,$session)){
                $user->session()->update($data['session']);
            }else if($token->revoked){
                $user->session()->update($data['session']);
            }else{
                /* Revoke token */
                $token->revoked = Yesno::YES;
                $token->save();

                $user->session()->update($data['session']);
            }
        }

        /* if Edit detail */
        if(isset($data['detail'])){
            $user->detail()->update($data['detail']);
        }

        return $user->update($data);
    }

    public  function searchSession($user, $params)
    {
        return $user->session()->where('ip', $params['ip'])->where('user_agent', $params['user_agent'])->first();
    }

    public  function searchToken($user, $params)
    {
        return $user->tokens()->where('id', $params['token_id'])->first();
    }

    public function createUser($data)
    {
        $user = $this->model()->create($data);
        $user->detail()->create(['user_id' => $user->id]);

        return $user->id;
    }

    public function getUserByWhere($query,$data)
    {
        return $this->model()->where($query,$data)->first();
    }

    public function getUserBySocialEmail($email, $provider)
    {
        return $this->model()->whereJsonContains('social', ['email' => $email])->whereJsonContains('social', ['provider' => $provider])->first();
    }

    public function removeSession($request)
    {
        return $request->user()->session()->delete();
    }

    public function logoutUser($request)
    {
        return $request->user()->token()->revoke();
    }

    public function sessionTokenCreate($request, $token, $user)
    {
        $session = TokenService::getUserAgent($request);

        $data['session'] = [
            'login_date' => Carbon::now(),
            'token_id'   => $token->id,
            'expires_at' => $token->expires_at
        ];

        $data['session'] = array_merge($data['session'], $session);

        return $this->editUserById($user->id, $data);
    }

    public function setUserAuthFlag($id)
    {
        $user = $this->getUser($id);

        if($user){
            $user->auth_flag = Yesno::YES;
            $user->save();
        }
    }

    public function deleteUserAuthFlag($id)
    {
        $user = $this->getUser($id);

        if($user){
            $user->auth_flag = Yesno::NO;
            $user->save();
        }
    }
}
