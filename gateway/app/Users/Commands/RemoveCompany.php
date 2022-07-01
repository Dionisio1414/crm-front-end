<?php

namespace App\Users\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use App\Users\Model\User\Company;
use App\Users\Model\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


class RemoveCompany extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:remove {company_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Company';

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
            $company = Company::find($this->argument('company_id'));

            if(!$company){
                $this->error('Company not found');
                return false;
            }

            Artisan::call('domain:remove', [
                'domain' => $company->domain,
                '--force' => 1
            ]);

            Artisan::call('database:drop', [
                'dbname' => $company->db_database
            ]);

            $this->info('Company has been removed!');

        } catch (ValidationException $e) {
            foreach($e->errors() as $messages) {
                foreach ($messages as $message) {
                    $this->error($message);
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
