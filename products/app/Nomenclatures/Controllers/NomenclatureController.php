<?php

namespace App\Nomenclatures\Controllers;

use App\Nomenclatures\Repositories\NomenclatureRepository;
use App\Categories\Repositories\CategoryRepository;
use App\Characteristics\Repositories\CharacteristicRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;
use App\Core\Helpers\ProductsTable;


class NomenclatureController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $nomenclatureRepository;
    private $categoryRepository;
    private $characteristicRepository;

    public function __construct(NomenclatureRepository $nomenclatureRepository, CategoryRepository $categoryRepository, CharacteristicRepository $characteristicRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->nomenclatureRepository = $nomenclatureRepository;
        $this->categoryRepository = $categoryRepository;
        $this->characteristicRepository = $characteristicRepository;

        app()->setLocale(request()->lang);
    }

    public function index(): JsonResponse
    {
        return $this->successResponse('test', Response::HTTP_OK);
    }

    public function indexProductsAll(): JsonResponse
    {
        $products = $this->nomenclatureRepository->getProductsAll();
        $productsHeader = $this->nomenclatureRepository->getProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexServicesAll(): JsonResponse
    {
        $services = $this->nomenclatureRepository->getServicesAll();
        $servicesHeader = $this->nomenclatureRepository->getServicesHeader();

        $rezult = (object)[
            'headers' => $servicesHeader,
            'body' => $services['body'],
            'total_page' => $services['total_page'],
            'total' => $services['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexKitsAll(): JsonResponse
    {
        $kits = $this->nomenclatureRepository->getKitsAll();
        $kitsHeader = $this->nomenclatureRepository->getKitsHeader();

        $rezult = (object)[
            'headers' => $kitsHeader,
            'body' => $kits['body'],
            'total_page' => $kits['total_page'],
            'total' => $kits['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexNotActualNomenclaturesAll(): JsonResponse
    {
        $nomenclatures = $this->nomenclatureRepository->getNomenclaturesAll();
        $nomenclaturesHeader = $this->nomenclatureRepository->getNomenclaturesHeader();

        $rezult = (object)[
            'headers' => $nomenclaturesHeader,
            'body' => $nomenclatures['body'],
            'total_page' => $nomenclatures['total_page'],
            'total' => $nomenclatures['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    //get nomenclature id
    public function indexProducts($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        $category_ids = $categories->getDescendants($categories);
        //$category_id = $this->recursiveCategories($categories);
       // dd($category_ids);
        $products = $this->nomenclatureRepository->getProducts($category_ids);
        $productsHeader = $this->nomenclatureRepository->getProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexServices($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);
        $services = $this->nomenclatureRepository->getServices($category_ids);

        $servicesHeader = $this->nomenclatureRepository->getServicesHeader();

        $rezult = (object)[
            'headers' => $servicesHeader,
            'body' => $services['body'],
            'total_page' => $services['total_page'],
            'total' => $services['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexKits($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);
        $kits = $this->nomenclatureRepository->getKits($category_ids);
        $kitsHeader = $this->nomenclatureRepository->getProductsHeader();

        $rezult = (object)[
            'headers' => $kitsHeader,
            'body' => $kits['body'],
            'total_page' => $kits['total_page'],
            'total' => $kits['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexSelectProducts(Request $request, $id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);

        if (isset($request->warehouse_id)) {
            $products = $this->nomenclatureRepository->getSelectProductsWarehouses($category_ids, $request->warehouse_id);
        } else {
            $products = $this->nomenclatureRepository->getSelectProducts($category_ids);
        }

        $productsHeader = $this->nomenclatureRepository->getSelectProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexSelectProductsAll(Request $request): JsonResponse
    {

        if (isset($request->warehouse_id)) {
            $products = $this->nomenclatureRepository->getSelectProductsAllWarehouses($request->warehouse_id);

        } else {
            $products = $this->nomenclatureRepository->getSelectProductsAll();

        }
        $productsHeader = $this->nomenclatureRepository->getSelectProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexSelectServices($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);

        $products = $this->nomenclatureRepository->getSelectServices($category_ids);
        $productsHeader = $this->nomenclatureRepository->getSelectServicesHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexSelectServicesAll(): JsonResponse
    {
        $products = $this->nomenclatureRepository->getSelectServicesAll();
        $productsHeader = $this->nomenclatureRepository->getSelectServicesHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function searchProductsDocument(Request $request): JsonResponse
    {
        if (isset($request->s)) {
            $products = $this->nomenclatureRepository->searchProductsDocument($request->s);

            if (isset($products)) {
                return $this->successResponse($products, Response::HTTP_OK);
            } else {
                return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
            }
        } else {
            return $this->successResponse($data = ['data' => [],], Response::HTTP_OK);
        }
    }

    public function searchProductsDocumentTable(Request $request): JsonResponse
    {
        if (isset($request->s)) {
            if (isset($request->category_id)) {
                $categories = $this->categoryRepository->getCategoryChildren($request->category_id);
                //$category_id = $this->recursiveCategories($categories);
                $category_ids = $categories->getDescendants($categories);
                $products = $this->nomenclatureRepository->searchProductsDocumentInCategoryTable($request->s, $category_ids);
            } else {
                $products = $this->nomenclatureRepository->searchProductsDocumentTable($request->s);
            }

            if (isset($products)) {
                $productsHeader = $this->nomenclatureRepository->getSelectProductsHeader();
                $rezult = (object)[
                    'headers' => $productsHeader,
                    'body' => $products['body'],
                    'total_page' => $products['total_page'],
                    'total' => $products['total']
                ];
                return $this->successResponse($rezult, Response::HTTP_OK);

            } else {
                return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
            }
        } else {
            return $this->successResponse($data = ['data' => [],], Response::HTTP_OK);
        }
    }

    public function tableStocksProduct($id): JsonResponse
    {
        $product = $this->nomenclatureRepository->tableStocksProduct($id);
        return $this->successResponse($product);
    }

    public function tableWriteOfStocksProduct($id): JsonResponse
    {
        $product = $this->nomenclatureRepository->tableWriteOfStocksProduct($id);
        return $this->successResponse($product);
    }

    public function indexNotActualNomenclatures($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);

        $nomenclatures = $this->nomenclatureRepository->getNomenclatures($category_ids);

        $nomenclaturesHeader = $this->nomenclatureRepository->getNomenclaturesHeader();

        $rezult = (object)[
            'headers' => $nomenclaturesHeader,
            'body' => $nomenclatures['body'],
            'total_page' => $nomenclatures['total_page'],
            'total' => $nomenclatures['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function recursiveCategories($categories)
    {
//        if (isset($categories->id)) {
//            $category_id[] = $categories->id;
//            dd($categories);
            if (isset($categories->children)) {
                foreach ($categories->children as $category) {
                    if (isset($category->id)) {
                        $category_id[] = $category->id;
                        if ($category->children->count() > 0) {
                            $this->recursiveCategories($category->children);
                        }
                    }
                    return $category_id;
                }
            }
//        }
    }

    public function findMainCategory($categories)
    {
        $category_id[] = $categories;
        if (isset($categories['parent'])) {
            $this->findMainCategory($categories['parent']);
        }

        return $category_id;
    }

    public function storeProduct(Request $request): JsonResponse
    {
        if (isset($request->sku)) {
            $validator = Validator::make($request->all(),
                [
                    'short_title' => 'required|unique:products|max:255',
                    'sku' => 'required|unique:products|max:255',
                    'dock_title' => 'required',
                ]
            );
        } else {
            $validator = Validator::make($request->all(),
                [
                    'short_title' => 'required|unique:products|max:255',
                    'dock_title' => 'required',
                ]
            );
        }

        if ($validator->fails()) {

            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->nomenclatureRepository->incrementNomenclature();
            $nomenclature = $this->nomenclatureRepository->createProduct($request->all());
            return $this->successResponse($nomenclature, Response::HTTP_CREATED);
            //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
        }
    }

    public function storeService(Request $request): JsonResponse
    {
        $this->nomenclatureRepository->incrementNomenclature();
        $nomenclature = $this->nomenclatureRepository->createService($request->all());
        return $this->successResponse($nomenclature, Response::HTTP_CREATED);
        //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
    }

    public function storeKit(Request $request): JsonResponse
    {
        $this->nomenclatureRepository->incrementNomenclature();
        $nomenclature = $this->nomenclatureRepository->createKit($request->all());
        return $this->successResponse($nomenclature, Response::HTTP_CREATED);
        //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
    }

    public function storeGroupProduct(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'short_title' => 'required|unique:products|max:255',
                //'sku' => 'required|unique:products|max:255',
                //'dock_title' => 'required',
            ]
        );

        if ($validator->fails()) {

            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->nomenclatureRepository->incrementNomenclature();
            $nomenclature = $this->nomenclatureRepository->createGroupProduct($request->all());
            return $this->successResponse($nomenclature, Response::HTTP_CREATED);
        }
    }

    public function storeGroupsProduct(Request $request, $id): JsonResponse
    {
        $group_nomenclature = $this->nomenclatureRepository->getGroupsNomenclature($id);
        if (isset($group_nomenclature)) {
            $group_explode = explode('/', $group_nomenclature->convert_id);
            $count = $group_explode[1];
        } else {
            $count = 0;
        }

        $request_all = $request->all();
        $product_array = [];
        foreach ($request_all['products'] as $product) {
            $check = $this->nomenclatureRepository->checkTitleSkuProduct($product['short_title'], $product['sku'] ?? null);
            if (!isset($check)) {
                $product_array['products'][] = $product;
            }
        }

        if (isset($product_array['products'])) {
            $nomenclatures = $this->nomenclatureRepository->createGroupsNomenclature($product_array, $id, $count);
            return $this->successResponse($nomenclatures, Response::HTTP_CREATED);
        } else {
            return $this->successResponse($this->nomenclatureRepository->getNomenclatureTable($id), Response::HTTP_CREATED);
        }
    }

    public function updateProduct(Request $request, $id): JsonResponse
    {
        $product_id = $this->nomenclatureRepository->getNomenclatureProductId($id)->id;

        if (isset($request->sku)) {
            $validator = Validator::make($request->all(),
                [
                    'short_title' => 'required|unique:products,short_title,' . $product_id . 'id|max:255',
                    //'sku' => 'required|unique:products,sku,' . $product_id . 'id|max:255',
                    // 'dock_title' => 'required',
                ]
            );
        } else {
            $validator = Validator::make($request->all(),
                [
                    'short_title' => 'required|unique:products,short_title,' . $product_id . 'id|max:255',
                    //'dock_title' => 'required',
                ]
            );
        }

        if ($validator->fails()) {

            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $nomenclature = $this->nomenclatureRepository->updateProduct($request->all(), $id);
            return $this->successResponse($nomenclature, Response::HTTP_CREATED);
            //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
        }
    }

    public function updateService(Request $request, $id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->updateService($request->all(), $id);
        return $this->successResponse($nomenclature, Response::HTTP_CREATED);
    }

    public function updateKit(Request $request, $id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->updateKit($request->all(), $id);
        return $this->successResponse($nomenclature, Response::HTTP_CREATED);
    }

    public function showProduct($id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->getNomenclature($id);

        return $this->successResponse($nomenclature, Response::HTTP_OK);
    }

    public function showService($id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->getNomenclatureService($id);
        return $this->successResponse($nomenclature, Response::HTTP_OK);
    }

    public function showKit($id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->getNomenclatureKit($id);
        return $this->successResponse($nomenclature, Response::HTTP_OK);
    }

    public function selectedKits(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $kits = $this->nomenclatureRepository->getSelectedKits();

        return $this->successResponse($kits, Response::HTTP_OK);
    }

    public function selectedCharacteristicsKits(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $item_ids = $this->nomenclatureRepository->getSelectedCharacteristicsKits();
        $characteristics = $this->characteristicRepository->getSelectedCharacteristicsKits($item_ids);

        return $this->successResponse($characteristics, Response::HTTP_OK);
    }

    public function getGroupsNomenclatures($id): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->getGroupsNomenclatures($id);

        return $this->successResponse($nomenclature, Response::HTTP_OK);
    }

    public function toVisibility(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->toVisibilityNomenclature($request->nomenclatures);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function outVisibility(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->outVisibilityNomenclature($request->nomenclatures);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function toArchive(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->toArchive($request->nomenclatures);
        //$nomenclatures = $this->nomenclatureRepository->getCategories();
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function toActual(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->toActual($request->nomenclatures);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function outArchive(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->outArchive($request->nomenclatures);
        //$nomenclatures = $this->nomenclatureRepository->getCategories();
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function outActual(Request $request): JsonResponse
    {
        $nomenclature = $this->nomenclatureRepository->outActual($request->nomenclatures);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function updateProductsHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateProductsHeader();

        return $this->successResponse($this->nomenclatureRepository->getProductsHeader());
    }

    public function updateServicesHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateServicesHeader();

        return $this->successResponse($this->nomenclatureRepository->getServicesHeader());
    }

    public function updateKitsHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateKitsHeader();

        return $this->successResponse($this->nomenclatureRepository->getKitsHeader());
    }

    public function updateNomenclaturesHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateNomenclaturesHeader();

        return $this->successResponse($this->nomenclatureRepository->getNomenclaturesHeader());
    }

    public function moveProducts($id)
    {
        $categories = $this->categoryRepository->getCategory($id);
        $products = $this->nomenclatureRepository->getNomenclaturesForMove();
        $this->categoryRepository->createCharacteristicPropertiesInCategory($id, $products);
        $this->nomenclatureRepository->moveProducts($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function moveServices($id)
    {
        $this->nomenclatureRepository->moveProducts($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function moveKits($id)
    {
        $categories = $this->categoryRepository->getCategory($id);
        $products = $this->nomenclatureRepository->getNomenclaturesForMove();
        $this->categoryRepository->createCharacteristicPropertiesInCategory($id, $products);
        $this->nomenclatureRepository->moveProducts($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function changeMinPriceNomenclature($id)
    {
        $change_price = $this->nomenclatureRepository->changeMinPriceNomenclature($id);
        return $this->successResponse('Successful operation', Response::HTTP_CREATED);
    }

    public function changePriceNomenclature(Request $request)
    {
        $change_price = $this->nomenclatureRepository->changePriceNomenclature($request->all());
        return $this->successResponse($change_price, Response::HTTP_CREATED);
    }

    public function changePriceNomenclatureHistory(Request $request)
    {
        $change_price_history = $this->nomenclatureRepository->changePriceNomenclatureHistory($request->all());
        return $this->successResponse($change_price_history, Response::HTTP_CREATED);
    }

    public function choosePriceNomenclature(Request $request)
    {
        $choose_price = $this->nomenclatureRepository->choosePriceNomenclature($request->all());
        return $this->successResponse($choose_price, Response::HTTP_CREATED);
    }

    public function storeAsyncProductsValidations(Request $request)
    {

        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:products|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successResponse('true', Response::HTTP_CREATED);
        }
    }

    public function updateAsyncProductsValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:products,' . $key . ',' . $request->id . 'id|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successResponse('true', Response::HTTP_CREATED);
            //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
        }
    }

    public function storeAsyncServicesValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:services|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return $this->successResponse('true', Response::HTTP_CREATED);
            //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
        }
    }

    public function updateAsyncServicesValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:services,' . $key . ',' . $request->id . 'id|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            return $this->successResponse('true', Response::HTTP_CREATED);
            //return $this->successResponse(ProductsTable::product($nomenclature), Response::HTTP_CREATED);
        }
    }

    //DOCUMENTS

    public function getProductsWithWarehouse($id)
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);
        $products = $this->nomenclatureRepository->getProductsWithWarehouse($category_ids);
        return $this->successResponse($products, Response::HTTP_CREATED);
    }

    public function selectProduct($id)
    {
        $products = $this->nomenclatureRepository->getProductWithWarehouse($id);
        return $this->successResponse($products, Response::HTTP_CREATED);
    }

    public function selectedOrdersNomenclatures(Request $request)
    {
        $nomenclatures = $this->nomenclatureRepository->selectedNomenclatures($request->nomenclatures);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getDocumentNomenclatureOrderStockHeader(),
            'body' => $nomenclatures,
        ];
        return $this->successResponse($rezult);
    }

    public function selectedNomenclatures(Request $request)
    {
        $nomenclatures = $this->nomenclatureRepository->selectedNomenclatures($request->nomenclatures);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getDocumentNomenclatureHeader(),
            'body' => $nomenclatures,
        ];
        return $this->successResponse($rezult);
    }

    public function selectedServices(Request $request)
    {

        $nomenclatures = $this->nomenclatureRepository->selectedServices($request->nomenclatures);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getDocumentNomenclatureHeader(),
            'body' => $nomenclatures,
        ];
        return $this->successResponse($rezult);
    }

    public function selectedWriteOfNomenclatures(Request $request)
    {
        $nomenclatures = $this->nomenclatureRepository->selectedNomenclatures($request->nomenclatures);
        // $documentHeader = $this->documentRepository->getDocumentNomenclatureHeader();
        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getDocumentNomenclatureStockHeader(),
            'body' => $nomenclatures,
        ];
        return $this->successResponse($rezult);
    }

    //related
    public function getProductsInRelatedOrAnalog($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);
        $products = $this->nomenclatureRepository->getProductsInRelatedOrAnalog($category_ids);
        $productsHeader = $this->nomenclatureRepository->getProductsInRelatedHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function getProductsInRelatedOrAnalogAll(): JsonResponse
    {
        $products = $this->nomenclatureRepository->getProductsInRelatedOrAnalogAll();
        $productsHeader = $this->nomenclatureRepository->getProductsInRelatedHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

//    public function getRelatedProductsAll(): JsonResponse
//    {
//        $products = $this->nomenclatureRepository->getRelatedProductsAll();
//        $productsHeader = $this->nomenclatureRepository->getRelatedProductsHeader();
//
//        $rezult = (object)[
//            'headers' => $productsHeader,
//            'body' => $products['body'],
////            'total_page' => $products['total_page'],
////            'total' => $products['total']
//        ];
//
//        return $this->successResponse($rezult, Response::HTTP_OK);
//    }

    public function getRelatedProducts($id): JsonResponse
    {
        $products = $this->nomenclatureRepository->getRelatedProducts($id);
        $productsHeader = $this->nomenclatureRepository->getRelatedProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products,
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function getTableAnalogProducts($id): JsonResponse
    {
        $products = $this->nomenclatureRepository->getTableAnalogProducts($id);
        return $this->successResponse($products, Response::HTTP_OK);
    }

    public function getTableRelatedProducts($id): JsonResponse
    {
        $products = $this->nomenclatureRepository->getTableRelatedProducts($id);
        return $this->successResponse($products, Response::HTTP_OK);
    }

    public function createRelatedProducts($id): JsonResponse
    {
        $this->nomenclatureRepository->createRelatedProducts($id);

        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
            'body' => $this->nomenclatureRepository->getRelatedProducts($id)
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function deleteRelatedProducts($id): JsonResponse
    {
        $this->nomenclatureRepository->deleteRelatedProducts($id);

        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
            'body' => $this->nomenclatureRepository->getRelatedProducts($id)
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function createAnalogProducts($id): JsonResponse
    {
        $this->nomenclatureRepository->createAnalogProducts($id);

        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
            'body' => $this->nomenclatureRepository->getAnalogProducts($id)
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function deleteAnalogProducts($id): JsonResponse
    {
        $this->nomenclatureRepository->deleteAnalogProducts($id);

        $rezult = (object)[
            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
            'body' => $this->nomenclatureRepository->getAnalogProducts($id)
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }
//    public function updateOrCreateRelatedProducts($id): JsonResponse
//    {
//        $this->nomenclatureRepository->updateOrCreateRelatedProducts($id);
//
//        $rezult = (object)[
//            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
//            'body' => $this->nomenclatureRepository->getRelatedProducts($id)
//        ];
//
//        return $this->successResponse($rezult, Response::HTTP_OK);
//    }

//    public function updateOrCreateAnalogProducts($id): JsonResponse
//    {
//        $this->nomenclatureRepository->updateOrCreateAnalogProducts($id);
//
//        $rezult = (object)[
//            'headers' => $this->nomenclatureRepository->getRelatedProductsHeader(),
//            'body' => $this->nomenclatureRepository->getAnalogProducts($id)
//        ];
//
//        return $this->successResponse($rezult, Response::HTTP_OK);
//    }

    public function updateRelatedProductsHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateRelatedProductsHeader();

        return $this->successResponse($this->nomenclatureRepository->getRelatedProductsHeader());
    }

    public function updateSelectRelatedProductsHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->nomenclatureRepository->updateSelectRelatedProductsHeader();

        return $this->successResponse($this->nomenclatureRepository->getSelectRelatedProductsHeader());
    }

    public function getTableRelatedProduct($id): JsonResponse
    {
        $product = $this->nomenclatureRepository->getTableRelatedProduct($id);
        return $this->successResponse($product, Response::HTTP_OK);
    }

    public function indexSelectRelatedProducts($id): JsonResponse
    {
        $categories = $this->categoryRepository->getCategoryChildren($id);
        //$category_id = $this->recursiveCategories($categories);
        $category_ids = $categories->getDescendants($categories);
        $products = $this->nomenclatureRepository->getSelectRelatedProducts($category_ids);
        $productsHeader = $this->nomenclatureRepository->getSelectRelatedProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function indexSelectRelatedProductsAll(): JsonResponse
    {
        $products = $this->nomenclatureRepository->getSelectRelatedProductsAll();
        $productsHeader = $this->nomenclatureRepository->getSelectRelatedProductsHeader();

        $rezult = (object)[
            'headers' => $productsHeader,
            'body' => $products['body'],
            'total_page' => $products['total_page'],
            'total' => $products['total']
        ];

        return $this->successResponse($rezult, Response::HTTP_OK);
    }

    public function selectedRelatedOrAnalogNomenclatures(Request $request)
    {
        if (isset($request->data)) {
            $array = $request->data;
        } else {
            $array = [];
        }

        $nomenclatures = $this->nomenclatureRepository->selectedRelatedOrAnalogNomenclatures($array);

        return $this->successResponse($nomenclatures);
    }

    public function selectedRelatedNomenclaturesInNomenclature(Request $request)
    {
        if (isset($request->data)) {
            $array = $request->data;
        } else {
            $array = [];
        }

        $nomenclatures = $this->nomenclatureRepository->selectedRelatedNomenclaturesInNomenclature($array);

        return $this->successResponse($nomenclatures);
    }
//
//    public function show($book) :JsonResponse
//    {
//        $book = Book::findOrFail($book);
//
//        return $this->successResponse($book);
//    }
//
//    public function update(Request $request, $book) :JsonResponse
//    {
//        $rules = [
//            'title'       => 'max:255',
//            'description' => 'max:255',
//            'price'       => 'min:0',
//            'author_id'   => 'min:1',
//        ];
//
//        $this->validate($request, $rules);
//        $book = Book::findOrFail($book);
//        $book->fill($request->all());
//
//        if ($book->isClean()) {
//            return $this->errorResponse('At least one value should change', Response::HTTP_UNPROCESSABLE_ENTITY);
//        }
//
//        $book->save();
//
//        return $this->successResponse($book, Response::HTTP_CREATED);
//    }
//
//    public function destroy($book) :JsonResponse
//    {
//        $book = Book::findOrFail($book);
//        $book->delete();
//
//        return $this->successResponse($book);
//    }
}
