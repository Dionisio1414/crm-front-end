<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\Artisan;

class CompanyServerCreator
{
    public static function _createCompanyServer($data=[])
    {
        $newDatabaseName  =  $data['database'] . '_' . $data['name'];

        /* Create subdomains user */
        Artisan::call('domain:add', [
            'domain' => $data['domain']
        ]);

        /* Create db and user */
        Artisan::call('database:create', [
            'dbname' => $newDatabaseName,
            'dbpassword' => $data['password']
        ]);

        /* Migrations company */
        Artisan::call('database:company_migrations', [
            'database' => $newDatabaseName
        ]);

        /* Seeds company */
        Artisan::call('database:company_seeds', [
            'edit'     => 'false',
            'database' => $newDatabaseName
        ]);

        /* Update .env user */
        $envData  = [
            'DB_DATABASE' => $newDatabaseName,
            'DB_USERNAME' => $newDatabaseName,
            'DB_PASSWORD' => $data['password'],
            'APP_URL'     => $data['domain']
        ];

        Artisan::call('domain:update_env', [
            'domain' => $data['domain'],
            '--domain_values' => json_encode($envData)
        ]);

        return $envData;
    }

    public static function _updateCompanyServer($data=[])
    {
        $newDatabaseName = $data['database'];

        /* Create subdomains user */
        Artisan::call('domain:add', [
            'domain' => $data['domain']
        ]);

        /* Update .env user */
        $envData  = [
            'DB_DATABASE' => $newDatabaseName,
            'DB_USERNAME' => $newDatabaseName,
            'DB_PASSWORD' => $data['password'],
            'APP_URL'     => $data['domain']
        ];

        Artisan::call('domain:update_env', [
            'domain' => $data['domain'],
            '--domain_values' => json_encode($envData)
        ]);
        return $envData;
    }

    public function _removeCompanyServer()
    {
        //TODO
    }
}
