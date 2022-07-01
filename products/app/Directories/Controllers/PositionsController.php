<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Positions\PositionsRepository;
use App\Directories\Repositories\Positions\PositionsHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class PositionsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $positionsRepository, $positionsHeaderRepository;

    public function __construct(PositionsRepository $positionsRepository,
                                PositionsHeaderRepository $positionsHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->positionsRepository = $positionsRepository;
        $this->positionsHeaderRepository = $positionsHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->positionsRepository->getItemsTable();
        $unitsHeader = $this->positionsHeaderRepository->getHeaders();

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
        $data = $this->positionsRepository->list();

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

        $cellMain = $this->positionsRepository->createItem();

        return $this->successResponse($this->positionsRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->positionsRepository->updateItem($id);

        return $this->successResponse($this->positionsRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->positionsHeaderRepository->updateHeaders();

        return $this->successResponse($this->positionsHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->positionsRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
