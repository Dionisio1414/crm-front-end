<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Regions\RegionRepository;
use App\Directories\Repositories\Regions\RegionHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class RegionsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $regionRepository, $regionHeaderRepository;

    public function __construct(RegionRepository $regionRepository, RegionHeaderRepository $regionHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->regionRepository = $regionRepository;
        $this->regionHeaderRepository = $regionHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->regionRepository->getItemsTable();
        $tableHeader = $this->regionHeaderRepository->getHeaders();

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
        $data = $this->regionRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->regionRepository->createItem();
        $this->regionRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->regionRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->regionRepository->updateItem($id);
        $this->regionRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->regionRepository->getItem($item->id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->regionHeaderRepository->updateHeaders();

        return $this->successResponse($this->regionHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->regionRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
