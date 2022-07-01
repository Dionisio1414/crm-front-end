<?php

namespace App\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Users\Model\User\Company;
use App\Core\Helpers\DatabaseConnection;

class CompanyMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:company_migrations {database?} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Company migration';

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

        try {

            if($this->argument('database')){
                // Make single migration company
                DatabaseConnection::setConnection([
                    'db_database'=>$this->argument('database')
                ]);

                Artisan::call('migrate', [
                    '--path' => 'database/migrations/company'
                ]);
            }else{
                // Get companies domain
                $companies = Company::all();

                // Make migrations all company
                foreach ($companies as $company) {
                    //Change connection db
                    DatabaseConnection::setConnection([
                        'db_database' => $company->db_database
                    ]);

                    Artisan::call('migrate', [
                        '--path' => 'database/migrations/company'
                    ]);
                }
            }

            //Change connection db
            DatabaseConnection::setConnection([
                'db_database' => getenv('DB_DATABASE')
            ]);

        } catch (\Exception $e) {
            DatabaseConnection::setConnection([
                'db_database' => getenv('DB_DATABASE')
            ]);
            $this->error($e->getMessage());
        }
    }
}
