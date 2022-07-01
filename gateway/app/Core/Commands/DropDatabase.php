<?php

namespace App\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class DropDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:drop {dbname} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop Database User';

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
            $connection = $this->hasArgument('connection') && $this->argument('connection') ? $this->argument('connection'): DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

            $hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$dbname."'");
            $hasUser = DB::connection($connection)->select("SELECT User FROM mysql.user WHERE user = '" . $dbname . "'");

            if($hasDb || $hasUser) {
                /* Remove database */
                DB::connection($connection)->select('DROP DATABASE '. $dbname);
                /* Remove user */
                DB::connection($connection)->select("DROP User '" . $dbname . "'@'%'");
                DB::connection($connection)->select("FLUSH PRIVILEGES");
                $this->info("Database '$dbname' remove for '$connection' connection");
            } else {
                $this->info("Database $dbname doesnt exists for $connection connection");
            }
        }
        catch (\Exception $e){
            $this->error($e->getMessage());
        }
    }
}
