<?php

namespace App\Nomenclatures\Controllers;

use App\Core\Traits\ApiResponder;
use App\Directories\Repositories\TypesPrices\TypesPriceRepository;
use App\Documents\Repositories\DocumentRepository;
use App\Nomenclatures\Models\NomenclaturePrice;
use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Nomenclatures\Repositories\PriceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class PriceController extends Controller
{
    use ApiResponder;

    protected $request, $typesPriceRepository, $nomenclatureRepository, $priceRepository, $documentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(TypesPriceRepository $typesPriceRepository, NomenclatureRepository $nomenclatureRepository, PriceRepository $priceRepository, DocumentRepository $documentRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        $this->request = request()->all();
        $this->typesPriceRepository = $typesPriceRepository;
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->priceRepository = $priceRepository;
        $this->documentRepository = $documentRepository;
    }

    public function getNomenclaturePrices(): JsonResponse
    {

        $rezult = (object)[
            'headers' => $this->priceRepository->getNomenclaturePricesTableHeader(),
            'body' => $this->priceRepository->getNomenclaturePrices()
        ];
        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function getNomenclatureCrmPrices(): JsonResponse
    {
        $rezult = (object)[
            'headers' => $this->priceRepository->getNomenclaturePricesHeader(),
            'body' => $this->priceRepository->getNomenclaturePrices()
        ];
        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function getNomenclaturePrice($id): JsonResponse
    {
        $nomenclatures = $this->priceRepository->getNomenclaturePrice($id);
        $rezult = (object)[
            'headers' => $this->priceRepository->getNomenclaturePricesHeader(),
            'body' => $nomenclatures['body'],
            'total_page' => $nomenclatures['total_page'],
            'total' => $nomenclatures['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

//    public function getNomenclaturePricesTable(): JsonResponse
//    {
//        $rezult = (object)[
//            'headers' => $this->priceRepository->getNomenclaturePrices(),
//            'body' => $this->priceRepository->getNomenclaturePricesTableHeader()
//        ];
//        return $this->successResponse($rezult, Response::HTTP_OK);
//    }

    public function updateNomenclaturePriceHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
        $this->priceRepository->updateNomenclaturePriceHeader();

        return $this->successResponse($this->priceRepository->getNomenclaturePricesHeader());
    }

    public function deletePrices(Request $request): JsonResponse
    {
        $this->priceRepository->deletePrices($request->data);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function getTableSettingPrices(Request $request): JsonResponse
    {
        $type_prices = $this->typesPriceRepository->getTypePrices();
        $headers = $this->typesPriceRepository->getTypePricesHeaders($type_prices);
        switch ($request->selection_nomenclature) {
            case NomenclaturePrice::DOCUMENTS:
                $nomenclatures = $this->documentRepository->getNomenclaturesDocuments();
                break;
            case NomenclaturePrice::CATEGORIES:
                $nomenclatures = $this->nomenclatureRepository->getNomenclaturesCategories();
                break;
            case NomenclaturePrice::PRICES:
                $nomenclatures = $this->priceRepository->getNomenclaturesPrices();
                break;
            case NomenclaturePrice::STOCKS:
                $nomenclatures = $this->nomenclatureRepository->getNomenclaturesWarehouses();
                break;
            case NomenclaturePrice::NOMENCLATURES:
                $nomenclatures = $this->nomenclatureRepository->getNomenclaturesSetting();
                break;
        }

        $result = (object)[
            'headers' => $headers,
            'body' => $nomenclatures['body'],
            'total_page' => $nomenclatures['total_page'],
            'total' => $nomenclatures['total']
        ];

        return $this->successResponse($result, Response::HTTP_CREATED);
    }

    public function storeSettingPrices(Request $request): JsonResponse
    {
        if (isset($request->nomenclatures)) {
            foreach ($request->nomenclatures as $key => $value) {
                if (isset($value['min_price'])) {
                    $this->nomenclatureRepository->changeMinPriceNomenclatureSetting($value['id'], $value['min_price']);
                }
                if (isset($value['prices'])) {
                    //dd($nomenclature->product->unit_id);
                    //$id, $price_id, $unit_id, $date, $value, $supplier_id
                    $nomenclature = $this->nomenclatureRepository->findNomenclature($value['id']);
                    foreach ($value['prices'] as $price) {
                        $this->priceRepository->updateOrCreateNomenclaturePriceSetting($value['id'], $price['id'], $nomenclature->product->unit_id ?? null, $request->date, $price['value'], $nomenclature->product->supplier_id ?? null);
                    }
                }
            }
            return $this->successResponse('Successful operation', Response::HTTP_CREATED);
        } else {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }
    }

}
