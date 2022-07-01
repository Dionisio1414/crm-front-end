<?php

namespace App\Directories\Controllers\Counterparties;

use App\Directories\Repositories\CounterpartiesCancellations\CounterpartiesCancellationsRepository;
use App\Directories\Repositories\CounterpartiesCancellations\CounterpartiesCancellationsHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class CancellationsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $counterpartiesCancellationsRepository, $counterpartiesCancellationsHeaderRepository;

    public function __construct(CounterpartiesCancellationsRepository $counterpartiesCancellationsRepository,
                                CounterpartiesCancellationsHeaderRepository $counterpartiesCancellationsHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->counterpartiesCancellationsRepository = $counterpartiesCancellationsRepository;
        $this->counterpartiesCancellationsHeaderRepository = $counterpartiesCancellationsHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->counterpartiesCancellationsRepository->getItemsTable();
        $unitsHeader = $this->counterpartiesCancellationsHeaderRepository->getHeaders();

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
        $data = $this->counterpartiesCancellationsRepository->list();

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

        $cellMain = $this->counterpartiesCancellationsRepository->createItem();

        return $this->successResponse($this->counterpartiesCancellationsRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesCancellationsRepository->updateItem($id);

        return $this->successResponse($this->counterpartiesCancellationsRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesCancellationsHeaderRepository->updateHeaders();

        return $this->successResponse($this->counterpartiesCancellationsHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesCancellationsRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
