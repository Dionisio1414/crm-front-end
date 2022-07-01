<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Counterparties\CounterpartiesRepository;
use App\Directories\Repositories\Counterparties\CounterpartiesHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class CounterpartiesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $counterpartiesRepository, $counterpartiesHeaderRepository;

    public function __construct(CounterpartiesRepository $counterpartiesRepository,
                                CounterpartiesHeaderRepository $counterpartiesHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->counterpartiesRepository = $counterpartiesRepository;
        $this->counterpartiesHeaderRepository = $counterpartiesHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $data = $this->counterpartiesRepository->getItemsTable();
        $dataHeader = $this->counterpartiesHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $dataHeader,
            'body'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->counterpartiesRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

//    public function store(Request $request)
//    {
//        if(!$request->cells){
//            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
//        }
//
//        $cellMain = $this->counterpartiesCancellationsRepository->createItem();
//
//        return $this->successResponse($this->counterpartiesCancellationsRepository->getItem($cellMain->id));
//    }
//
//    public function update(Request $request, $id)
//    {
//        if(!$request->cells){
//            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
//        }
//
//        $this->counterpartiesCancellationsRepository->updateItem($id);
//
//        return $this->successResponse($this->counterpartiesCancellationsRepository->getItem($id));
//    }
//
//    public function updateHeader(Request $request)
//    {
//        if(!$request->data){
//            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
//        }
//
//        $this->counterpartiesCancellationsHeaderRepository->updateHeaders();
//
//        return $this->successResponse($this->counterpartiesCancellationsHeaderRepository->getHeaders());
//    }
//
//    public function toArchive(Request $request)
//    {
//        if(!$request->data){
//            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
//        }
//
//        $this->counterpartiesCancellationsRepository->toArchive();
//
//        return $this->successMessage('Successful operation', Response::HTTP_OK);
//    }

}
