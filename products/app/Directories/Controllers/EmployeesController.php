<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Employees\EmployeeRepository;
use App\Directories\Repositories\Employees\EmployeeHeaderRepository;
use App\Directories\Repositories\Users\UserRepository;
use App\Core\Traits\ApiResponder;
use App\Services\Service\CoreUsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $employeeRepository, $userRepository, $employeeHeaderRepository, $coreUsersService;

    public function __construct(
        EmployeeRepository $employeeRepository,
        UserRepository $userRepository,
        EmployeeHeaderRepository $employeeHeaderRepository,
        CoreUsersService $coreUsersService
    )
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->employeeRepository = $employeeRepository;
        $this->userRepository = $userRepository;
        $this->employeeHeaderRepository = $employeeHeaderRepository;
        $this->coreUsersService = $coreUsersService;

        app()->setLocale(request()->lang);
    }

    public function getTable(): JsonResponse
    {
        $table = $this->employeeRepository->getItemsTable();
        $tableHeader = $this->employeeHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $tableHeader,
            'body'    => $table['data'],
            'total_page'   => $table['last_page'],
            'total'   => $table['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->employeeRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->employeeRepository->createItem();
        $this->employeeRepository->createOrUpdateMain($item->id);
        $this->employeeRepository->createOrUpdateContact($item->id);
        $this->employeeRepository->createOrUpdateDocuments($item->id);
        $this->employeeRepository->createOrUpdateCells($item->id);

        /* Create User */
        if($item->is_user){
            $directoryUser = [
                'directory_employee_id' => $item->id,
                'archive' => false
            ];
            $directoryUser = $this->userRepository->createItem($directoryUser);
            $this->userRepository->createOrUpdateCells($directoryUser->id);

            if($request->is_step){
                /* Create Default Chat */
                DB::table('chat_user')->insert([
                    ['chat_id' => 1, 'user_id' => 1]
                ]);
            }else{
                /* Create User Gateway */
                $gatewayUser = $this->coreUsersService->createGatewayUser($request->all());
                if(optional($gatewayUser)->data){
                    $this->userRepository->updateItem($directoryUser->id, [
                        'gateway_user_id' => $gatewayUser->data->id,
                        'is_invited'      => true
                    ]);
                    $employee = $this->employeeRepository->getItem($item->id);
                    $employee['invite_url'] = $gatewayUser->data->invite_url;

                    return $this->successResponse($employee);
                }
            }
        }

        return $this->successResponse($this->employeeRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->employeeRepository->updateItem($id);
        $this->employeeRepository->createOrUpdateMain($item->id);
        $this->employeeRepository->createOrUpdateContact($item->id);
        $this->employeeRepository->createOrUpdateDocuments($item->id);
        $this->employeeRepository->createOrUpdateCells($item->id);

        /* Update User */
        if($item->is_user){
            $directoryUser = $this->userRepository->getItemByEmployeeId($item->id);
            $this->userRepository->createOrUpdateCells($directoryUser->id);
            /* Update User Gateway */
            $this->coreUsersService->updateGatewayUser($directoryUser->gateway_user_id, $request->all());
        }

        return $this->successResponse($this->employeeRepository->getItem($item->id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->employeeHeaderRepository->updateHeaders();

        return $this->successResponse($this->employeeHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->employeeRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function storeAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $table = 'directory_employees_details_main';
            switch ($key) {
                case 'mobile_phone':
                    $table = 'directory_employees_details_contacts';
                    break;
                case 'email':
                    $table = 'directory_employees_details_contacts';
                    break;
            }

            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'unique:' . $table . ',' . $key . '|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }

    public function updateAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $table = 'directory_employees_details_main';
            switch ($key) {
                case 'mobile_phone':
                    $table = 'directory_employees_details_contacts';
                    break;
                case 'email':
                    $table = 'directory_employees_details_contacts';
                    break;
            }

            $id = '';
            if($request->id){
                if($employee = $this->employeeRepository->getItem($request->id)){
                    if($table == 'directory_employees_details_main' && optional($employee->main)->id){
                        $id = $employee->main->id;
                    }else if($table == 'directory_employees_details_contacts' && optional($employee->contact)->id){
                        $id = $employee->contact->id;
                    }
                }
            }

            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'unique:' . $table . ',' . $key . ',' . $id . 'id|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }
}
