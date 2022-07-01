<?php

namespace App\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:create {dbname} {dbpassword} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database User';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            $dbname = $this->argument('dbname');
            $dbpassword = $this->argument('dbpassword');
            $connection = $this->hasArgument('connection') && $this->argument('connection') ? $this->argument('connection'): DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

            $hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$dbname."'");
            $hasUser = DB::connection($connection)->select("SELECT User FROM mysql.user WHERE user = '" . $dbname . "'");


            if(empty($hasDb) || empty($hasUser)) {
                /* Create database */
                DB::connection($connection)->select('CREATE DATABASE '. $dbname);

                /* Create user */
                DB::connection($connection)->select("CREATE USER '" . $dbname . "'@'%' IDENTIFIED BY '" . $dbpassword . "' ");
                DB::connection($connection)->select("GRANT ALL PRIVILEGES ON " . $dbname . ".* TO '" . $dbname . "'@'localhost'");
                DB::connection($connection)->select("FLUSH PRIVILEGES");

                $this->info("User and Database '$dbname' created for '$connection' connection");
            }
            else {
                $this->info("User and Database $dbname already exists for $connection connection");
            }
        }
        catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
}
