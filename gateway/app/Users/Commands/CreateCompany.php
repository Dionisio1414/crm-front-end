<?php
namespace App\Users\Commands;

use Illuminate\Console\Command;
use App\Users\Model\User;
use App\Users\Model\User\Company;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Core\Helpers\CompanyServerCreator;

class CreateCompany extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Company';

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
            $data['company']['name'] = $this->ask('Company Name *');

            Validator::make($data['company'], [
                'name'      => ['required', 'string', 'max:255', 'unique:companies'],
            ])->validate();

            $data['user'] = [
                'first_name'   => $this->ask('User First Name *'),
                'last_name'    => $this->ask('User Last Name'),
                'email'        => $this->ask('User email *'),
                'phone'        => $this->ask('User Phone Number'),
                'password'     => $this->secret('User password *'),
                'api_token'    => null,
                'role_id'      => null,
            ];

            Validator::make($data['user'], [
                'first_name'   => ['required', 'string', 'max:255'],
                'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone'        => ['string', 'max:255', 'unique:users'],
                'password'     => ['required', 'string', 'min:8'],
            ])->validate();

            /* Create user */
            $data['user']['password'] = Hash::make($data['user']['password']);
            $user = User::create($data['user']);

            /* Create company */
            $data['company']['domain']   =  mb_strtolower($data['company']['name']) . '.' . getenv('DOMAIN_URL');
            $data['company']['owner_id'] =  $user->id;

            $company = Company::create($data['company']);

            /* Update User owner company */
            $user->company_id = $company->id;
            $user->save();

            /* Create server */
            $serverData = [
                'domain'   => $data['company']['domain'],
                'database' => getenv('DB_DATABASE'),
                'name'     => mb_strtolower($company->name),
                'password' => Str::random(8),
            ];
            $databaseData = CompanyServerCreator::_createUserServer($serverData);

            /* Update Company db info */
            $company->update([
                'db_database' => $databaseData['DB_DATABASE'],
                'db_username' => $databaseData['DB_USERNAME'],
                'db_password' => $databaseData['DB_PASSWORD'],
            ]);

            $this->info('Company has been created successfully!');
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
