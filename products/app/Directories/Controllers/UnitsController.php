<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Units\UnitRepository;
use App\Directories\Repositories\Units\UnitHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class UnitsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $unitRepository, $unitHeaderRepository;

    public function __construct(UnitRepository $unitRepository, UnitHeaderRepository $unitHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->unitRepository = $unitRepository;
        $this->unitHeaderRepository = $unitHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->unitRepository->getItemsTable();
        $unitsHeader = $this->unitHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $unitsHeader,
            'body'    => $units['data'],
            'total_page'   => $units['last_page'],
            'total'   => $units['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->unitRepository->list();

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

        $cellMain = $this->unitRepository->createItem();

        return $this->successResponse($this->unitRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->unitRepository->updateItem($id);

        return $this->successResponse($this->unitRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->unitHeaderRepository->updateHeaders();

        return $this->successResponse($this->unitHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->unitRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
