<?php

namespace App\Users\Repositories;

use App\Core\Helpers\CompanyCreator;
use App\Core\Helpers\TokenService;
use App\Users\Model\User;
use App\Users\Model\User\Company as Model;
use App\Core\Repositories\CoreRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

/**
 * Class CharacteristicRepository.
 */
class CompanyRepository extends CoreRepository
{
    /**
     * @return string
     *  Return the model
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    public function getCompany($id)
    {
        return $this->model()->find($id);
    }

    public function updateCompany($id, $data=[])
    {
        return $this->getCompany($id)->update($data);
    }

    public function generateCompany($id, $data, $request=null, $user=null)
    {
        $company = $this->getCompany($id);

        /* if Regenerate Company Domain */
        if(isset($data['domain'])
            && $data['domain'] != $company->domain
            && strpos($data['domain'], env('DOMAIN_URL')) !== false
            && trim($data['domain']) !=  '.' . env('DOMAIN_URL')
            && trim($data['domain']) != env('DOMAIN_URL')
            && $data['domain']
            && trim($data['domain']) != ''
            ){

            /* Validate domains */
            $validator = Validator::make($data,
                [
                    'domain' => 'regex:/^[a-z-.0-9]+$/',
                ]
            );

            if(!$validator->fails()) {
                CompanyCreator::_updateCompany($company, $data['domain']);
                $userRepository = new UserRepository();
            }

            $data['domain'] = str_replace(' ', '', $data['domain']);

            if(!$user){
                $userRepository->deleteUserAuthFlag($company->owner_id);
            }else{
                $userRepository->deleteUserAuthFlag($user->id);
            }
        }else{
            unset($data['domain']);
        }

        if(!isset($data['name'])){
            unset($data['name']);
        }

        if(!isset($data['from_admin'])){
            $this->updateCompany($id, $data);
        }

        return $this->getCompany($id);
    }

    public function validate($data)
    {
        $rules = [
            'name' => isset($data['name']) ? ['required', 'string', 'max:255'] : [],
            'language_interface_id' => isset($data['language_interface_id']) ? ['required', 'integer'] : [],
            'currency_id' => isset($data['currency_id']) ? ['required', 'integer'] : [],
            'domain' => isset($data['domain']) ? ['required', 'max:255', 'regex:/^[a-z-.0-9]+$/'] : [],
            'data_prohibition' => isset($data['data_prohibition']) ? ['nullable' ,'string', 'max:255'] : [],
            'archive_cleare' => isset($data['archive_cleare']) ? ['nullable', 'integer'] : [],
        ];

        if (isset($data['domain'])) {
            $data['domain'] = str_replace(' ', '', $data['domain']);
        }

        $validator = Validator::make($data, $rules);

        return $validator;
    }

}
