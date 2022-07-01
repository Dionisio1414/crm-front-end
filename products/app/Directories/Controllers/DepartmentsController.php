<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Departments\DepartmentRepository;
use App\Directories\Repositories\Departments\DepartmentHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class DepartmentsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $departmentRepository, $departmentHeaderRepository;

    public function __construct(DepartmentRepository $departmentRepository, DepartmentHeaderRepository $departmentHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->departmentRepository = $departmentRepository;
        $this->departmentHeaderRepository = $departmentHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->departmentRepository->getItemsTable();
        $tableHeader = $this->departmentHeaderRepository->getHeaders();

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
        $data = $this->departmentRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $cellMain = $this->departmentRepository->createItem();

        return $this->successResponse($this->departmentRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->departmentRepository->updateItem($id);

        return $this->successResponse($this->departmentRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->departmentHeaderRepository->updateHeaders();

        return $this->successResponse($this->departmentHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->departmentRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
