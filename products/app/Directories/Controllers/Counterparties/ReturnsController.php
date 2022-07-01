<?php

namespace App\Directories\Controllers\Counterparties;

use App\Directories\Repositories\CounterpartiesReturns\CounterpartiesReturnsRepository;
use App\Directories\Repositories\CounterpartiesReturns\CounterpartiesReturnsHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class ReturnsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $counterpartiesReturnsRepository, $counterpartiesReturnsHeaderRepository;

    public function __construct(CounterpartiesReturnsRepository $counterpartiesReturnsRepository,
                                CounterpartiesReturnsHeaderRepository $counterpartiesReturnsHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->counterpartiesReturnsRepository = $counterpartiesReturnsRepository;
        $this->counterpartiesReturnsHeaderRepository = $counterpartiesReturnsHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->counterpartiesReturnsRepository->getItemsTable();
        $unitsHeader = $this->counterpartiesReturnsHeaderRepository->getHeaders();

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
        $data = $this->counterpartiesReturnsRepository->list();

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

        $cellMain = $this->counterpartiesReturnsRepository->createItem();

        return $this->successResponse($this->counterpartiesReturnsRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesReturnsRepository->updateItem($id);

        return $this->successResponse($this->counterpartiesReturnsRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesReturnsHeaderRepository->updateHeaders();

        return $this->successResponse($this->counterpartiesReturnsHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesReturnsRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
