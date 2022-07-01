<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use PDO;

class DatabaseConnection
{

    public static function setConnection($params=[])
    {
        $connection =  DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

        config([
            'database.connections.mysql.database' => $params['db_database']
        ]);
        DB::purge($connection);

        return DB::connection($connection);
    }

    public static function getConnection()
    {
        return config('database.connections.mysql.database');
    }
}
