<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Cities\CityRepository;
use App\Directories\Repositories\Cities\CityHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class CitiesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $cityRepository, $cityHeaderRepository;

    public function __construct(CityRepository $cityRepository,CityHeaderRepository $cityHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->cityRepository = $cityRepository;
        $this->cityHeaderRepository = $cityHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->cityRepository->getItemsTable();
        $tableHeader = $this->cityHeaderRepository->getHeaders();

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
        $data = $this->cityRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->cityRepository->createItem();
        $this->cityRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->cityRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->cityRepository->updateItem($id);
        $this->cityRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->cityRepository->getItem($item->id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->cityHeaderRepository->updateHeaders();

        return $this->successResponse($this->cityHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->cityRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
