<?php

namespace App\Documents\Controllers;

use App\Documents\Models\Document;
use App\Documents\Repositories\DocumentRepository;
use App\Documents\Repositories\Purchases\PurchaseRepository;
use App\Documents\Repositories\Purchases\PurchaseHeaderRepository;
use App\Core\Traits\ApiResponder;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Nomenclatures\Repositories\PriceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use App\Core\Model\Yesno;


class PurchasesController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $purchaseRepository, $purchaseHeaderRepository, $documentRepository, $nomenclatureRepository, $priceRepository;

    public function __construct(PurchaseRepository $purchaseRepository,
                                PurchaseHeaderRepository $purchaseHeaderRepository,
                                DocumentRepository $documentRepository,
                                NomenclatureRepository $nomenclatureRepository,
                                PriceRepository $priceRepository)
    {
        /* Set database */
        if ($db = request('db')) {
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->purchaseRepository = $purchaseRepository;
        $this->purchaseHeaderRepository = $purchaseHeaderRepository;
        $this->documentRepository = $documentRepository;
        $this->nomenclatureRepository  = $nomenclatureRepository;
        $this->priceRepository = $priceRepository;
    }

    public function getTable(): JsonResponse
    {
        $purchases = $this->purchaseRepository->getItemsTable();

        $purchaseHeader = $this->purchaseHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $purchaseHeader,
            'body' => $purchases['data'],
            'total_page' => $purchases['last_page'],
            'total' => $purchases['total']
        ];

        return $this->successResponse($rezult);
    }

    public function storeDocument(Request $request)
    {
        //$request->request->add(['date'=>$request->document_date]);

        $document = $this->documentRepository->createDocument();
        $item = $this->purchaseRepository->createItem($document->id);
        $this->purchaseRepository->createOrUpdateDelivery($item->id);

        if(isset($request->warehouse_id)){
            $this->purchaseRepository->createOrUpdateCells($item->id, $request->nomenclatures ?? null);
            $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            //$request->request->add(['price_id'=>$request->price_type_id]);
            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
        }
        $getDocument = $this->purchaseRepository->getItem($item->id);
        $getDocument->type = Document::PURCHASES;

        return $this->successResponse($getDocument);
    }

    public function updateDocument(Request $request, $id)
    {

       // $request->request->add(['date'=>$request->document_date]);

        //dd($request->all());
        $document = $this->documentRepository->getDocument($id);
        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->purchaseRepository->updateItem($id);
            $this->purchaseRepository->createOrUpdateDelivery($document_id);

            $this->documentRepository->updateDocument($id, $request->date);

            if(isset($request->warehouse_id)){
                $this->purchaseRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            }
           // $request->request->add(['price_id'=>$request->price_type_id]);
            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);

            $getDocument = $this->purchaseRepository->getItem($document_id);
            $getDocument->type = Document::PURCHASES;

            return $this->successResponse($getDocument);
        } else {
            $purchase_id = $this->purchaseRepository->updateCapitalizedDocument($id);
            $this->purchaseRepository->createOrUpdateDelivery($purchase_id);
            $document_id = $this->purchaseRepository->getDocumentId($id)->id;
            $getDocument = $this->purchaseRepository->getItem($document_id);
            $getDocument->type = Document::PURCHASES;
            return $this->successResponse($getDocument, Response::HTTP_OK);
        }
    }

    public function storeCapitalizedDocument(Request $request)
    {
//        $request->request->add(['date'=>$request->document_date]);
//        $request->request->add(['price_id'=>$request->price_type_id]);

        $document = $this->documentRepository->createCapitalizedDocument();
        $item = $this->purchaseRepository->createItem($document->id);

        if(isset($request->warehouse_id)) {
            $this->purchaseRepository->createOrUpdateCells($item->id, $request->nomenclatures ?? null);
            $document_nomenclatures = $this->documentRepository->createCapitalizedDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            $this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
        }

        $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);

        $getDocument = $this->purchaseRepository->getItem($item->id);
        $getDocument->type = Document::PURCHASES;

        return $this->successResponse($getDocument);
    }

    public function capitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);

//        $request->request->add(['date'=>$request->document_date]);
//        $request->request->add(['price_id'=>$request->price_type_id]);

        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->purchaseRepository->updateItem($id);
            $this->purchaseRepository->createOrUpdateDelivery($document_id);

            $this->documentRepository->updateDocument($id, $request->date);

            if(isset($request->warehouse_id)) {
                $this->purchaseRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
                $this->nomenclatureRepository->createNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            }

            //$request->request->add(['date'=>$request->create_document_date]);

            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
            $this->documentRepository->capitalizedDocument($id);

            $getDocument = $this->purchaseRepository->getItem($document_id);
            $getDocument->type = Document::PURCHASES;

            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function canceledCapitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocumentReceiptStocks($id);

        if ($document->status == Document::CAPITALIZED) {
            $this->nomenclatureRepository->canceledCapitalizedStocks($document->nomenclatures, $document->document_purchases->warehouse_id);
            $this->documentRepository->canceledCapitalizedDocument($id);

            $getDocument = $this->purchaseRepository->getItem($document->document_purchases->id);
            $getDocument->type = Document::PURCHASES;
            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDocumentTable($id)
    {
        $document =  $this->documentRepository->getDocumentTable($id);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        return $this->successResponse($document);
    }

    public function getActivityPurchases()
    {
        $purchases = $this->purchaseRepository->getItemsTable();
        $purchaseHeader = $this->purchaseHeaderRepository->getActivityPurchasesHeaders();

        $rezult = (object)[
            'headers' => $purchaseHeader,
            'body' => $purchases['data'],
            'total_page' => $purchases['last_page'],
            'total' => $purchases['total']
        ];

        return $this->successResponse($rezult);
    }

    public function updateHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->purchaseHeaderRepository->updateHeaders();

        return $this->successResponse($this->purchaseHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->purchaseRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
