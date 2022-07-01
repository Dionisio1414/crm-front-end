<?php

namespace App\Core\Helpers;

use App\Users\Model\User\Company;
use App\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CompanyCreator
{
    public static function _createCompany($user, $successData = null)
    {
        /* Generate company */
        $data = [];
        $data['company']['uniq_id']  =  md5(uniqid(rand(),1));
        $data['company']['name']     =  mb_strtolower(Str::random(5));
        $data['company']['domain']   =  $data['company']['name'] . '.' . getenv('DOMAIN_URL');
        $data['company']['owner_id'] =  $user->id;

        $company = Company::create($data['company']);

        /* Update User owner company */
        $user->company_id = $company->id;
        $user->save();

        /* Create server */
        $serverData = [
            'domain'   => $data['company']['domain'],
            'database' => getenv('DB_DATABASE'),
            'name'     => $company->name,
            'password' => Str::random(8),
        ];

        if($successData){
            $userRepository = new UserRepository();
            $userNew = $userRepository->getUser($user->id);
            $successData['user'] = $userNew;
            EndResponse::respondOK($successData);
        }



        $databaseData = CompanyServerCreator::_createCompanyServer($serverData);

        /* Update Company db info */
        $company->update([
            'db_database' => $databaseData['DB_DATABASE'],
            'db_username' => $databaseData['DB_USERNAME'],
            'db_password' => $databaseData['DB_PASSWORD'],
        ]);

        /* Send notification websocket */
        broadcast(new \App\Events\Company\CompanyCreate($user,true));

        return $user;
    }

    public static function _updateCompany(Company $company, $new_domain)
    {
        Artisan::call('company:update', [
            'company_id' => $company->id
        ]);

        $data = [
            'domain'   => $new_domain,
            'database' => $company->db_database,
            'password' => $company->db_password,
        ];

        CompanyServerCreator::_updateCompanyServer($data);
    }

    public static function _removeCompany(Company $company)
    {
        Artisan::call('company:remove', [
            'company_id' => $company->id
        ]);

        return true;
    }
}
