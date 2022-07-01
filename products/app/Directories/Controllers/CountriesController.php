<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Countries\CountryRepository;
use App\Directories\Repositories\Countries\CountryHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class CountriesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $countryRepository, $countryHeaderRepository;

    public function __construct(CountryRepository $countryRepository,CountryHeaderRepository $countryHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->countryRepository = $countryRepository;
        $this->countryHeaderRepository = $countryHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->countryRepository->getItemsTable();
        $tableHeader = $this->countryHeaderRepository->getHeaders();

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
        $data = $this->countryRepository->list();

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
//        $cellMain = $this->countryRepository->createItem();
//
//        return $this->successResponse($this->countryRepository->getItem($cellMain->id));
//    }

    public function update(Request $request, $id)
    {
        $this->countryRepository->updateItem($id);
        return $this->successResponse($this->countryRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->countryHeaderRepository->updateHeaders();

        return $this->successResponse($this->countryHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->countryRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
