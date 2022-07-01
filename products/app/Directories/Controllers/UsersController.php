<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Users\UserRepository;
use App\Directories\Repositories\Users\UserHeaderRepository;
use App\Directories\Repositories\Employees\EmployeeRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use App\Services\Service\CoreUsersService;

class UsersController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $userRepository, $employeeRepository, $userHeaderRepository, $coreUsersService;

    public function __construct(UserRepository $userRepository,
                                EmployeeRepository $employeeRepository,
                                UserHeaderRepository $userHeaderRepository,
                                CoreUsersService $coreUsersService)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->userRepository = $userRepository;
        $this->employeeRepository = $employeeRepository;
        $this->userHeaderRepository = $userHeaderRepository;
        $this->coreUsersService = $coreUsersService;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->userRepository->getItemsTable();
        $tableHeader = $this->userHeaderRepository->getHeaders();

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
        $data = $this->userRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function update(Request $request, $id)
    {
        if($request->by_gateway_user_id){
            $item = $this->userRepository->updateItemByGatewayUserId($request->by_gateway_user_id);
        }else{
            $item = $this->userRepository->updateItem($id);
        }

        /*  Update Employee */
        $this->employeeRepository->createOrUpdateMain($item->employee->id);
        $this->employeeRepository->createOrUpdateContact($item->employee->id);
        $this->employeeRepository->createOrUpdateCells($item->employee->id);

        $this->userRepository->createOrUpdateCells($item->id);

        /* Update User Gateway */
        $gatewayUser = $this->coreUsersService->updateGatewayUser($item->gateway_user_id, $request->all());
        if(optional($gatewayUser)->data){
            return $this->successResponse($this->userRepository->getItem($item->id));
        }

        return $this->successResponse($this->userRepository->getItem($item->id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->userHeaderRepository->updateHeaders();

        return $this->successResponse($this->userHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->userRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }
}
