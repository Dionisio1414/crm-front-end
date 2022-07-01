<?php

namespace App\Core\Traits;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

trait ValidateEmailPhone
{
    public function isEmptyPhoneOrEmail($data)
    {
        if(!isset($data['email']) && !isset($data['phone'])){
            return __('validation.custom.login.required');
        }

        return false;
    }

    public function validatePhoneOrEmail($data, $user_id = null)
    {
        return  Validator::make($data, $this->rules($data,$user_id));
    }

    public function validatePhone($request, $data, $check_password = false)
    {
        try{
            $data['phone'] = $this->makeFormat($request, $data['phone']);
        }catch(\Exception $ex){
            $messages = $ex->getMessage();
            $data['phone'] = null;
        }

        if(!$data['phone']){
            return __('validation.phone');
        }

        if(!$check_password){
            $validator = Validator::make($data, $this->rules($data));
            if($validator->fails()){
                return $validator->getMessageBag()->toArray();
            }
        }

        return $data;
    }

    public function validatePhoneData($request, $data)
    {
        try{
            $data = $this->makeFormatData($request, $data['phone']);
        }catch(\Exception $ex){
            $messages = $ex->getMessage();
            $data['phone'] = null;
        }

        if(!$data['phone']){
            return __('validation.phone');
        }

        return $data;
    }

    public function validateAuth($user_id, $data = null)
    {
        $validator = $data ? Validator::make($data, $this->rulesAuth($user_id, $data)) : null;

        if(!$validator){
            return [
                'error' => 'Bad Request'
            ];
        }

        if($validator->fails()){
            return [
                'error' => $validator->getMessageBag()->toArray()
            ];
        }

        return true;
    }

    public function rules($data, $user_id = null)
    {
        return [
            'email' => isset($data['email']) ? ['required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($user_id)
            ] : [],
            'phone' => isset($data['phone']) ? [
                Rule::unique('users')->ignore($user_id),
                'required',
                Rule::phone()->country($this->countries->getCountryCodes())->type('mobile'),
                'numeric'
            ] : [],
        ];
    }

    public function rulesAuth($user_id, $data=null, $is_ignore_unique = false)
    {
        return [
            'first_name'   => isset($data['first_name']) ? ['required', 'string', 'max:255'] : [],
            'last_name'    => isset($data['last_name']) ? ['required', 'string', 'max:255'] : [],
            'middle_name'  => isset($data['middle_name']) ? ['required', 'string', 'max:255'] : [],
            'company_name' => isset($data['company_name']) ? ['required', 'string', 'max:255'] : [],
            'domain'       => isset($data['domain']) ? ['required', 'string', 'max:255'] : [],
            'position_id'  => isset($data['position_id']) ? ['required', 'integer'] : [],
            'email'        => isset($data['email']) ? ['required', 'string', 'email', 'max:255',
                !$is_ignore_unique ? Rule::unique('users')->ignore($user_id) : ''
            ] : [],
            'phone'        => isset($data['phone']) ? [
                'required',
                !$is_ignore_unique ? Rule::unique('users')->ignore($user_id) : '',
                Rule::phone()->country($this->countries->getCountryCodes())->type('mobile'),
                'numeric'
            ] : [],
        ];
    }
}
