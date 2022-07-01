<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use PDO;

class DatabaseConnection
{

    public static function setConnection($params=[])
    {
        config([
            'database.connections.mysql.database' => $params['db_database']
        ]);
        DB::purge();
        return DB::connection('mysql');
    }
}
