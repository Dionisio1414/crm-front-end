<?php

namespace App\Documents\Controllers;

use App\Core\Model\Yesno;
use App\Documents\Models\Document;
use App\Documents\Repositories\DocumentRepository;
use App\Documents\Repositories\WriteOffStocks\WriteOffStockRepository;
use App\Documents\Repositories\WriteOffStocks\WriteOffStockHeaderRepository;
use App\Core\Traits\ApiResponder;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Nomenclatures\Repositories\PriceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;


class WriteOffStocksController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $writeOffStockRepository, $writeOffStockHeaderRepository, $nomenclatureRepository, $documentRepository, $priceRepository;

    public function __construct(WriteOffStockRepository $writeOffStockRepository, WriteOffStockHeaderRepository $writeOffStockHeaderRepository, NomenclatureRepository $nomenclatureRepository, DocumentRepository $documentRepository, PriceRepository $priceRepository)
    {
        /* Set database */
        if ($db = request('db')) {
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->documentRepository = $documentRepository;
        $this->writeOffStockRepository = $writeOffStockRepository;
        $this->writeOffStockHeaderRepository = $writeOffStockHeaderRepository;
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->priceRepository = $priceRepository;
    }

    public function storeCapitalizedDocument(Request $request)
    {
        $document = $this->documentRepository->createCapitalizedDocument();
        $cellMain = $this->writeOffStockRepository->createItem($document->id);
        if(isset($request->warehouse_id)) {
            $this->writeOffStockRepository->createOrUpdateCells($cellMain->id, $request->nomenclatures ?? null);
            $document_nomenclatures = $this->documentRepository->createCapitalizedDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            //$this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures, $request->warehouse_id);
        }
        //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
        $getDocument = $this->writeOffStockRepository->getItem($cellMain->id);
        $getDocument->type = Document::WRITE_OFF_STOCKS;

        return $this->successResponse($getDocument);
    }

    public function storeDocument(Request $request)
    {
        $document = $this->documentRepository->createDocument();
        $cellMain = $this->writeOffStockRepository->createItem($document->id);

        if(isset($request->warehouse_id)){
            $this->writeOffStockRepository->createOrUpdateCells($cellMain->id, $request->nomenclatures ?? null);
            $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
        }

        //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);

        $getDocument = $this->writeOffStockRepository->getItem($cellMain->id);
        $getDocument->type = Document::WRITE_OFF_STOCKS;

        return $this->successResponse($getDocument);
    }

    public function updateDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);
        //dd($document);
        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->writeOffStockRepository->updateItem($id);
            $this->documentRepository->updateDocument($id, $request->date);
            if(isset($request->warehouse_id)){
                $this->writeOffStockRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            }
            //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
            $getDocument = $this->writeOffStockRepository->getItem($document_id);
            $getDocument->type = Document::WRITE_OFF_STOCKS;

            return $this->successResponse($getDocument);
        } elseif (isset($request->comments)){
            $this->writeOffStockRepository->updateCommentDocument($id, $request->comments);
            $document_id = $this->writeOffStockRepository->getDocumentId($id)->id;
            $getDocument = $this->writeOffStockRepository->getItem($document_id);
            return $this->successResponse($getDocument, Response::HTTP_OK);
        } else {
            return $this->errorResponse('Error', Response::HTTP_BAD_REQUEST);
        }
    }

    public function capitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);

        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->writeOffStockRepository->updateItem($id);
            $this->documentRepository->updateDocument($id, $request->date);
            if(isset($request->warehouse_id)) {
                $this->writeOffStockRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
                $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            }
            //$this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
            $this->documentRepository->capitalizedDocument($id);

            $getDocument = $this->writeOffStockRepository->getItem($document_id);
            $getDocument->type = Document::WRITE_OFF_STOCKS;

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
            $this->nomenclatureRepository->canceledCapitalizedWriteOffStocks($document->nomenclatures, $document->document_write_off_stocks->warehouse_id);
            $this->documentRepository->canceledCapitalizedDocument($id);

            $getDocument = $this->writeOffStockRepository->getItem($document->document_write_off_stocks->id);
            $getDocument->type = Document::WRITE_OFF_STOCKS;
            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDocumentTable($id)
    {
        $document = $this->writeOffStockRepository->getDocumentId($id);
        $data =  $this->documentRepository->getDocumentTable($id, $document->warehouse_id ?? null);
       // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        return $this->successResponse($data);
    }

    public function getSelectDocumentTable($id)
    {
        $document =  $this->documentRepository->getSelectDocumentReceiptStocksTable($id);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();

        return $this->successResponse($document);
    }

    //directories function

    public function getTable(): JsonResponse
    {
        $units = $this->writeOffStockRepository->getItemsTable();
        $unitsHeader = $this->writeOffStockHeaderRepository->getHeaders();

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
        return $this->writeOffStockRepository->modelVisible()->get();
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

        $this->writeOffStockHeaderRepository->updateHeaders();

        return $this->successResponse($this->writeOffStockHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->writeOffStockRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
