<?php

namespace App\Documents\Controllers;

use App\Core\Model\Yesno;
use App\Documents\Models\Document;
use App\Documents\Repositories\DocumentRepository;
use App\Documents\Repositories\Orders\OrderRepository;
use App\Documents\Repositories\Orders\OrderHeaderRepository;
use App\Core\Traits\ApiResponder;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use App\Nomenclatures\Repositories\PriceRepository;

class OrdersController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $orderRepository, $orderHeaderRepository, $documentRepository, $nomenclatureRepository, $priceRepository;

    public function __construct(OrderRepository $orderRepository,
                                OrderHeaderRepository $orderHeaderRepository,
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

        $this->orderRepository = $orderRepository;
        $this->orderHeaderRepository = $orderHeaderRepository;
        $this->documentRepository = $documentRepository;
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->priceRepository = $priceRepository;
    }

    public function getTable(): JsonResponse
    {
        $orders = $this->orderRepository->getItemsTable();
        $orderHeader = $this->orderHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $orderHeader,
            'body' => $orders['data'],
            'total_page' => $orders['last_page'],
            'total' => $orders['total']
        ];

        return $this->successResponse($rezult);
    }

    public function storeDocument(Request $request)
    {
        $document = $this->documentRepository->createDocument();
        $item = $this->orderRepository->createItem($document->id);
        $this->orderRepository->createOrUpdateDelivery($item->id);

        if (isset($request->warehouse_id)) {
            $this->orderRepository->createOrUpdateCells($item->id, $request->nomenclatures ?? null);
            $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
            if ($request->is_reserve) {
                $this->nomenclatureRepository->incrementReserveNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            }
        }

        $getDocument = $this->orderRepository->getItem($item->id);
        $getDocument->type = Document::ORDERS;

        return $this->successResponse($getDocument);
    }

    public function updateDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);
        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->orderRepository->updateItem($id);
            $this->orderRepository->createOrUpdateDelivery($document_id);
            $this->documentRepository->updateDocument($id, $request->date);
            if (isset($request->warehouse_id)) {
                $this->orderRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                $getDocument = $this->orderRepository->getItem($document_id);
                if ($request->is_reserve) {
                    if ($getDocument->is_reserve) {
                        $document_nomenclatures = $this->documentRepository->getDocumentNomenclatures($id);
                        $arr_reserve = [];
                        foreach ($document_nomenclatures->nomenclatures as $key => $val) {
                            foreach ($request->nomenclatures as $k => $v) {
                                if ($val->id == $v['id']) {
                                    $arr_reserve[$val->id]['balance_base'] = $v['balance_base'] - $val->pivot->balance;
                                    $arr_reserve[$val->id]['id'] = $val->id;
                                }
                            }
                        }
                        $this->nomenclatureRepository->incrementReserveNomenclatureStocks($arr_reserve ?? null, $request->warehouse_id);
                    }
                } else {
                    if ($getDocument->is_reserve) {
                        $document_nomenclatures = $this->documentRepository->getDocumentNomenclatures($id);
                        $this->nomenclatureRepository->decrementReserveNomenclatureStocks($document_nomenclatures->nomenclatures ?? null, $getDocument->warehouse_id);
                    }
                }
                $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            }

            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::NO, $document->id);
            $getDocument = $this->orderRepository->getItem($document_id);
            $getDocument->type = Document::ORDERS;

            return $this->successResponse($getDocument);
        } else {
            $purchase_id = $this->orderRepository->updateCapitalizedDocument($id);
            $this->orderRepository->createOrUpdateDelivery($purchase_id);
            $document_id = $this->orderRepository->getDocumentId($id)->id;
            $getDocument = $this->orderRepository->getItem($document_id);
            $getDocument->type = Document::ORDERS;
            return $this->successResponse($getDocument, Response::HTTP_OK);
        }
    }

    public function storeCapitalizedDocument(Request $request)
    {
        //?? reserve
        $document = $this->documentRepository->createCapitalizedDocument();
        $item = $this->orderRepository->createItem($document->id);

        if (isset($request->warehouse_id)) {
            $this->orderRepository->createOrUpdateCells($item->id, $request->nomenclatures ?? null);
            $document_nomenclatures = $this->documentRepository->createCapitalizedDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
            $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
        }

        $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
        $getDocument = $this->orderRepository->getItem($item->id);
        $getDocument->type = Document::ORDERS;

        return $this->successResponse($getDocument);
    }

    public function capitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocument($id);

        if ($document->status != Document::CAPITALIZED) {
            $document_id = $this->orderRepository->updateItem($id);
            $this->orderRepository->createOrUpdateDelivery($document_id);

            $this->documentRepository->updateDocument($id, $request->date);

            if (isset($request->warehouse_id)) {
                $this->orderRepository->createOrUpdateCells($document_id, $request->nomenclatures ?? null);
                if ($request->is_reserve) {
                    $getDocument = $this->orderRepository->getItem($document_id);
                    if ($getDocument->is_reserve) {
                        $document_nomenclatures = $this->documentRepository->getDocumentNomenclatures($id);
                        $arr_reserve = [];
                        foreach ($document_nomenclatures->nomenclatures as $key => $val) {
                            foreach ($request->nomenclatures as $k => $v) {
                                if ($val->id == $v['id']) {
                                    $arr_reserve[$val->id]['balance_base'] = $v['balance_base'] - $val->pivot->balance;
                                    $arr_reserve[$val->id]['id'] = $val->id;
                                }
                            }
                        }
                        $this->nomenclatureRepository->decrementReserveNomenclatureStocks($arr_reserve ?? null, $request->warehouse_id);
                    }
                }
                $document_nomenclatures = $this->documentRepository->createDocNomen($document, $request->nomenclatures ?? null, $request->warehouse_id);
                $this->nomenclatureRepository->writeOffNomenclatureStocks($request->nomenclatures ?? null, $request->warehouse_id);
            }

            $this->priceRepository->editNomenclaturePrice($request, $status = Yesno::YES, $document->id);
            $this->documentRepository->capitalizedDocument($id);

            $getDocument = $this->orderRepository->getItem($document_id);
            $getDocument->type = Document::ORDERS;

            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function canceledCapitalizedDocument(Request $request, $id)
    {
        $document = $this->documentRepository->getDocumentReceiptStocks($id);

        if ($document->status == Document::CAPITALIZED) {
            $this->nomenclatureRepository->canceledCapitalizedWriteOffStocks($document->nomenclatures, $document->document_orders->warehouse_id);
            $this->documentRepository->canceledCapitalizedDocument($id);
            if ($document->document_orders->is_reserve) {
                $this->nomenclatureRepository->incrementReserveNomenclatureStocks($document->nomenclatures ?? null, $document->document_orders->warehouse_id);
            }
            $getDocument = $this->orderRepository->getItem($document->document_orders->id);
            $getDocument->type = Document::ORDERS;
            return $this->successResponse($getDocument);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

    public function getDocumentTable($id)
    {
        $document = $this->documentRepository->getDocumentTable($id);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        return $this->successResponse($document);
    }

    public function updateHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->orderHeaderRepository->updateHeaders();
        return $this->successResponse($this->orderHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->orderRepository->toArchive();
        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function updateNomenclaturesInBasket(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $basketOrders = $this->orderRepository->updateNomenclaturesInBasket($request->data);
        return $this->successMessage($basketOrders, Response::HTTP_OK);
    }

    public function clearBasket()
    {
        $this->orderRepository->clearBasket();
        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function openBasket()
    {
        $data = $this->orderRepository->openBasket();
        $table = $this->documentRepository->getOpenBasket($data);
        return $this->successResponse($table);
    }

    public function generalCountBasket()
    {
        $count = $this->orderRepository->openBasket()->count();
        if(isset($count)){
            return $this->successResponse($count);
        }else{
            return $this->successResponse(0);
        }
    }

    public function getShipmentOrders()
    {
        $orders = $this->orderRepository->getItemsStatusShipmentTable();
        $orderHeader = $this->orderHeaderRepository->getOrdersShipmentHeaders();

        $rezult = (object)[
            'headers' => $orderHeader,
            'body' => $orders['data'],
            'total_page' => $orders['last_page'],
            'total' => $orders['total']
        ];

        return $this->successResponse($rezult);
    }
}
