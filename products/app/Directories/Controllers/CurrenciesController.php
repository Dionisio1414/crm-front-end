<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Currencies\CurrencyRepository;
use App\Directories\Repositories\Currencies\CurrencyHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class CurrenciesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $currencyRepository, $currencyHeaderRepository;

    public function __construct(CurrencyRepository $currencyRepository, CurrencyHeaderRepository $currencyHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->currencyRepository = $currencyRepository;
        $this->currencyHeaderRepository = $currencyHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->currencyRepository->getItemsTable();
        $unitsHeader = $this->currencyHeaderRepository->getHeaders();

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
        $data = $this->currencyRepository->list();

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

        $cellMain = $this->currencyRepository->createItem();

        return $this->successResponse($this->currencyRepository->getItem($cellMain->id));
    }

    public function update(Request $request, $id)
    {
        if(!$request->cells){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->currencyRepository->updateItem($id);

        return $this->successResponse($this->currencyRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->currencyHeaderRepository->updateHeaders();

        return $this->successResponse($this->currencyHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->currencyRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
