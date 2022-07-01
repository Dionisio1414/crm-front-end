<?php

namespace App\Directories\Controllers\Counterparties;

use App\Directories\Repositories\CounterpartiesContracts\CounterpartiesContractsRepository;
use App\Directories\Repositories\CounterpartiesContracts\CounterpartiesContractsHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class ContractsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $counterpartiesContractsRepository, $counterpartiesContractsHeaderRepository;

    public function __construct(CounterpartiesContractsRepository $counterpartiesContractsRepository,
                                CounterpartiesContractsHeaderRepository $counterpartiesContractsHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->counterpartiesContractsRepository = $counterpartiesContractsRepository;
        $this->counterpartiesContractsHeaderRepository = $counterpartiesContractsHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->counterpartiesContractsRepository->getItemsTable();
        $unitsHeader = $this->counterpartiesContractsHeaderRepository->getHeaders();

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
        $data = $this->counterpartiesContractsRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->counterpartiesContractsRepository->createItem();
        $this->counterpartiesContractsRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->counterpartiesContractsRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->counterpartiesContractsRepository->updateItem($id);
        $this->counterpartiesContractsRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->counterpartiesContractsRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesContractsHeaderRepository->updateHeaders();

        return $this->successResponse($this->counterpartiesContractsHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->counterpartiesContractsRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
