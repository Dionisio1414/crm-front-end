<?php

namespace App\Directories\Controllers\Prices;

use App\Directories\Repositories\TypesPrices\TypesPriceRepository;
use App\Directories\Repositories\TypesPrices\TypesPriceHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class TypesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $typesPriceRepository, $typesPriceHeaderRepository;

    public function __construct(TypesPriceRepository $typesPriceRepository, TypesPriceHeaderRepository $typesPriceHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->typesPriceRepository = $typesPriceRepository;
        $this->typesPriceHeaderRepository = $typesPriceHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $data = $this->typesPriceRepository->getItemsTable();
        $dataHeader = $this->typesPriceHeaderRepository->getHeaders();

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
        $data = $this->typesPriceRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->typesPriceRepository->createItem();
        $this->typesPriceRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->typesPriceRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->typesPriceRepository->updateItem($id);

        if(!$request->is_cells){
            $this->typesPriceRepository->createOrUpdateCells($item->id);
        }

        return $this->successResponse($this->typesPriceRepository->getItem($item->id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->typesPriceHeaderRepository->updateHeaders();

        return $this->successResponse($this->typesPriceHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->typesPriceRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
