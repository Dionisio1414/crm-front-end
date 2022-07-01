<?php

namespace App\Documents\Controllers;

use App\Documents\Repositories\DocumentRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class DocumentController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $documentRepository;

    public function __construct(DocumentRepository $documentRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->documentRepository = $documentRepository;
    }

    public function getWarehouseDocuments($id)
    {
        $warehouses = $this->documentRepository->getWarehouseDocuments($id);
        $warehousesHeader = $this->documentRepository->getHeaders();

        $rezult = (object)[
            'headers' => $warehousesHeader,
            'body'    => $warehouses->data,
            'total_page' => $warehouses->lastPage(),
            'total' => $warehouses->total()
        ];

        return $this->successResponse($rezult);
//        $warehouses = $this->documentRepository->getDocumentReceiptStockWarehouse($id);
//        return $this->successResponse($warehouses, Response::HTTP_CREATED);
    }

    public function getWarehouseDocumentsAll()
    {
        $warehouses = $this->documentRepository->getWarehouseDocumentsAll();
        $warehousesHeader = $this->documentRepository->getHeaders();

        $rezult = (object)[
            'headers' => $warehousesHeader,
            'body'    => $warehouses->data,
            'total_page' => $warehouses->lastPage(),
            'total' => $warehouses->total()
        ];

        return $this->successResponse($rezult);
    }

    public function getDocumentsAll()
    {
        $warehouses = $this->documentRepository->getDocumentsAll();
        $warehousesHeader = $this->documentRepository->getDocumentAllHeader();

        $rezult = (object)[
            'headers' => $warehousesHeader,
            'body'    => $warehouses->data,
            'total_page' => $warehouses->lastPage(),
            'total' => $warehouses->total()
        ];

        return $this->successResponse($rezult);
    }

    public function updateDocumentNomenclaturesHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentRepository->updateDocumentNomenclaturesHeader();

        return $this->successResponse($this->documentRepository->getDocumentNomenclatureHeader());
    }

    public function updateDocumentWarehousesHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentRepository->updateDocumentWarehousesHeader();

        return $this->successResponse($this->documentRepository->getHeaders());
    }

    public function updateDocumentAllHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentRepository->updateDocumentAllHeader();

        return $this->successResponse($this->documentRepository->getDocumentAllHeader());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }
}
