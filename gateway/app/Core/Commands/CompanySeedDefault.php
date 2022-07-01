<?php

namespace App\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Users\Model\User\Company;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Config;

class CompanySeedDefault extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:company_seeds_default {class} {database?} {connection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds Company';

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

                Config::set('app.class_seeds', $this->argument('class'));
                Artisan::call('db:seed');
                Config::set('app.class_seeds', false);

            }else{
                // Get companies domain
                $companies = Company::all();

                // Make migrations all company
                foreach ($companies as $company) {
                    //Change connection db
                    DatabaseConnection::setConnection([
                        'db_database' => $company->db_database
                    ]);

                    Config::set('app.class_seeds', $this->argument('class'));
                    Artisan::call('db:seed');
                    Config::set('app.class_seeds', false);
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
