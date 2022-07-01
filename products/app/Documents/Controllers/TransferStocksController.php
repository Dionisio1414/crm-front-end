<?php

namespace App\Documents\Controllers;

use App\Core\Model\Yesno;
use App\Documents\Models\Document;
use App\Documents\Repositories\DocumentRepository;
use App\Documents\Repositories\TransferStocks\TransferStockRepository;
use App\Documents\Repositories\TransferStocks\TransferStockHeaderRepository;
use App\Core\Traits\ApiResponder;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Nomenclatures\Repositories\PriceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;


class TransferStocksController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $transferStockRepository, $transferStockHeaderRepository, $nomenclatureRepository, $documentRepository, $priceRepository;

    public function __construct(TransferStockRepository $transferStockRepository, TransferStockHeaderRepository $transferStockHeaderRepository, NomenclatureRepository $nomenclatureRepository, DocumentRepository $documentRepository, PriceRepository $priceRepository)
    {
        /* Set database */
        if ($db = request('db')) {
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->documentRepository = $documentRepository;
        $this->transferStockRepository = $transferStockRepository;
        $this->transferStockHeaderRepository = $transferStockHeaderRepository;
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->priceRepository = $priceRepository;
    }

    public function storeCapitalizedDocument(Request $request)
    {
        $document = $this->documentRepository->createCapitalizedDocument();
        $cellMain = $this->transferStockRepository->createItem($document->id);
        if (isset($request->warehouse_id)) {
            $this->transferStockRepository->createOrUpdateCells($cellMain->id, $request->nomenclatures ?? null);
            $document_nomenclatures = $this->documentRepository->createCapitalizedDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            $this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures ?? null, $request->to_warehouse_id);
        }
        //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
        $getDocument = $this->transferStockRepository->getItem($cellMain->id);
        $getDocument->type = Document::TRANSFER_STOCKS;

        return $this->successResponse($getDocument);
    }

    public function storeDocument(Request $request)
    {
        $document = $this->documentRepository->createDocument();
        $cellMain = $this->transferStockRepository->createItem($document->id);
        $this->transferStockRepository->createOrUpdateDelivery($cellMain->id);

        if (isset($request->warehouse_id)) {
            $this->transferStockRepository->createOrUpdateCells($cellMain->id, $request->nomenclatures ?? null);
            $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
        }

        //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
        $getDocument = $this->transferStockRepository->getItem($cellMain->id);
        $getDocument->type = Document::TRANSFER_STOCKS;

        return $this->successResponse($getDocument);
    }

    public function updateDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);
        //dd($document);
        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->transferStockRepository->updateItem($id);
            $this->transferStockRepository->createOrUpdateDelivery($document_id);
            $this->documentRepository->updateDocument($id, $request->date);
            if (isset($request->warehouse_id)) {
                $this->transferStockRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            }
            //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
            $getDocument = $this->transferStockRepository->getItem($document_id);
            $getDocument->type = Document::TRANSFER_STOCKS;

            return $this->successResponse($getDocument);
        } else {
            $purchase_id = $this->transferStockRepository->updateCapitalizedDocument($id);
            $this->transferStockRepository->createOrUpdateDelivery($purchase_id);
            $document_id = $this->transferStockRepository->getDocumentId($id)->id;
            $getDocument = $this->transferStockRepository->getItem($document_id);
            $getDocument->type = Document::TRANSFER_STOCKS;
            return $this->successResponse($getDocument, Response::HTTP_OK);
        }
    }

    public function capitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);

        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->transferStockRepository->updateItem($id);
            $this->transferStockRepository->createOrUpdateDelivery($document_id);

            $this->documentRepository->updateDocument($id, $request->date);
            if (isset($request->warehouse_id)) {
                $this->transferStockRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
                $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
                $this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures ?? null, $request->to_warehouse_id);

            }
            //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
            $this->documentRepository->capitalizedDocument($id);

            $getDocument = $this->transferStockRepository->getItem($document_id);
            $getDocument->type = Document::TRANSFER_STOCKS;

            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function canceledCapitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocumentReceiptStocks($id);

        if ($document->status == Document::CAPITALIZED) {
            //$document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures, $request->warehouse_id);
            $this->nomenclatureRepository->canceledCapitalizedWriteOffStocks($document->nomenclatures, $document->document_transfer_stocks->warehouse_id);
            $this->nomenclatureRepository->canceledCapitalizedStocks($document->nomenclatures, $document->document_transfer_stocks->to_warehouse_id);

            $this->documentRepository->canceledCapitalizedDocument($id);

            $getDocument = $this->transferStockRepository->getItem($document->document_transfer_stocks->id);
            $getDocument->type = Document::TRANSFER_STOCKS;
            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDocumentTable($id)
    {
        $document = $this->transferStockRepository->getDocumentId($id);
        $data = $this->documentRepository->getDocumentTable($id, $document->warehouse_id ?? null);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        return $this->successResponse($data);
    }

    public function getSelectDocumentTable($id)
    {
        $document = $this->documentRepository->getSelectDocumentReceiptStocksTable($id);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();

        return $this->successResponse($document);
    }

    //directories function

    public function getTable(): JsonResponse
    {
        $units = $this->transferStockRepository->getItemsTable();
        $unitsHeader = $this->transferStockHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $unitsHeader,
            'body' => $units['data'],
            'total_page' => $units['last_page'],
            'total' => $units['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        return $this->transferStockRepository->modelVisible()->get();
    }

//    public function store(Request $request)
//    {
//        if (!$request->cells) {
//            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
//        }
//
//        $document = $this->documentRepository->createDocument();
//        $cellMain = $this->receiptStockRepository->createItem($document->id);
//        $document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures, $request->warehouse_id);
//        $this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures, $request->warehouse_id);
//        return $this->successResponse($this->receiptStockRepository->getItem($cellMain->id));
//    }
//
//    public function update(Request $request, $id)
//    {
//        $this->receiptStockRepository->updateItem($id);
//
//        return $this->successResponse($this->receiptStockRepository->getItem($id));
//    }

    public function updateHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->transferStockHeaderRepository->updateHeaders();

        return $this->successResponse($this->transferStockHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->transferStockRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
