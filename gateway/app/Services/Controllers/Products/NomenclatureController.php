<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\NomenclatureService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    use ApiResponder;

    public $nomenclatureService;

    /**
     * Create a new controller instance.
     *
     * @param NomenclatureService $nomenclatureService
     */
    public function __construct(NomenclatureService $nomenclatureService)
    {
        $this->nomenclatureService = $nomenclatureService;
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get Products in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-services/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get Services in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Services",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexServices($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getServices($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-kits/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get kits in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get kits in category id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                        property="total_page",
     *                        title="total_page",
     *                        example=1,
     *                        type="int"
     *                  ),
     *                  @OA\Property(
     *                        property="total",
     *                        title="total",
     *                        example=1,
     *                        type="int"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexKits($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getKits($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-not-actual-nomenclatures/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get not actual nomenclatures in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Services",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexNotActualNomenclatures($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getNotActualNomenclaturesServices($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/update-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Update product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="lower_limit",
     *                    title="Lower limit",
     *                    type="number",
     *                    example=10,
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="weight",
     *                    title="Weight",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="volume",
     *                    title="Volume",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="general_count",
     *                    title="General Count",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="reserve",
     *                    title="Reserve",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="barcode_supplier",
     *                    title="Barcode supplier",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="barcode",
     *                    title="Barcode",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="packing",
     *                    title="Packing",
     *                    type="string",
     *                    example="10 шт"
     *               ),
     *               @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier Fullness",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier Successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="weight_id",
     *                    title="Weight ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="volume_id",
     *                    title="Volume ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="country_id",
     *                    title="Country ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="property_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="base_characteristic_value",
     *                          @OA\Property(
     *                              property="is_color",
     *                              title="is_color",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                          property="ids",
     *                          @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          ),
     *                          ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *
     *              @OA\Property(
     *                     property="base_characteristic",
     *                          @OA\Property(
     *                          property="ids",
     *                          @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          ),
     *                          ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateProduct($domain, $id)
    {
        return $this->successResponse($this->nomenclatureService->updateProduct($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/update-service/{id}",
     *      tags={"Nomenclature"},
     *      summary="Update service",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateService($domain, $id)
    {
        return $this->successResponse($this->nomenclatureService->updateService($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/update-kit/{id}",
     *      tags={"Nomenclature"},
     *      summary="Update kit",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to update kits",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="lower_limit",
     *                    title="Lower limit",
     *                    type="number",
     *                    example=10,
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="weight",
     *                    title="Weight",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="volume",
     *                    title="Volume",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="packing",
     *                    title="Packing",
     *                    type="string",
     *                    example="10 шт"
     *               ),
     *               @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier Fullness",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier Successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="weight_id",
     *                    title="Weight ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="country_id",
     *                    title="Country ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="property_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *              @OA\Property(
     *                     property="kits",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="count",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateKit($domain, $id)
    {
        return $this->successResponse($this->nomenclatureService->updateKit($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-product",
     *      tags={"Nomenclature"},
     *      summary="Create product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="lower_limit",
     *                    title="Lower limit",
     *                    type="number",
     *                    example=10,
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="weight",
     *                    title="Weight",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="volume",
     *                    title="Volume",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="general_count",
     *                    title="General Count",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="reserve",
     *                    title="Reserve",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="barcode_supplier",
     *                    title="Barcode supplier",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="barcode",
     *                    title="Barcode",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="packing",
     *                    title="Packing",
     *                    type="string",
     *                    example="10 шт"
     *               ),
     *               @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier Fullness",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier Successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="weight_id",
     *                    title="Weight ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="volume_id",
     *                    title="Volume ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="country_id",
     *                    title="Country ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="property_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="base_characteristic_value",
     *                          @OA\Property(
     *                              property="is_color",
     *                              title="is_color",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                          property="ids",
     *                          @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          ),
     *                          ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeProduct(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeProduct());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-service",
     *      tags={"Nomenclature"},
     *      summary="Create service",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                     title="Title",
     *                     type="string",
     *                     example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeService(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeService());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-kit",
     *      tags={"Nomenclature"},
     *      summary="Create kit",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create kits",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="lower_limit",
     *                    title="Lower limit",
     *                    type="number",
     *                    example=10,
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="weight",
     *                    title="Weight",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="volume",
     *                    title="Volume",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="packing",
     *                    title="Packing",
     *                    type="string",
     *                    example="10 шт"
     *               ),
     *               @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier Fullness",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier Successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="weight_id",
     *                    title="Weight ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="country_id",
     *                    title="Country ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="property_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *              @OA\Property(
     *                     property="kits",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="count",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *              @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeKit(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeKit());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-group-product",
     *      tags={"Nomenclature"},
     *      summary="Create group product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="category_id",
     *                    title="Category ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="dock_title",
     *                     title="Title",
     *                     type="string",
     *                     example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="lower_limit",
     *                    title="Lower limit",
     *                    type="number",
     *                    example=10,
     *               ),
     *              @OA\Property(
     *                    property="min_price",
     *                    title="Min Price",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="weight",
     *                    title="Weight",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="volume",
     *                    title="Volume",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="general_count",
     *                    title="General Count",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="reserve",
     *                    title="Reserve",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="barcode_supplier",
     *                    title="Barcode supplier",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="barcode",
     *                    title="Barcode",
     *                    type="string",
     *                    example="1TRTY55"
     *               ),
     *               @OA\Property(
     *                    property="packing",
     *                    title="Packing",
     *                    type="string",
     *                    example="10 шт"
     *               ),
     *               @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier Fullness",
     *                    type="number",
     *                    example=50,
     *               ),
     *               @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier Successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="is_visible",
     *                    title="is_visible",
     *                    example=true,
     *                    type="boolean"
     *              ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="supplier_id",
     *                    title="Supplier ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="weight_id",
     *                    title="Weight ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="volume_id",
     *                    title="Volume ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="country_id",
     *                    title="Country ID",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                     property="property_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="base_characteristic_value",
     *                          @OA\Property(
     *                              property="is_color",
     *                              title="is_color",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                          property="ids",
     *                          @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          ),
     *                          ),
     *               ),
     *              @OA\Property(
     *                     property="base_characteristic",
     *                          @OA\Property(
     *                          property="ids",
     *                          @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          ),
     *                          ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *               @OA\Property(
     *                     property="characteristic",
     *                        @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                        )
     *               ),
     *              @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="value",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeGroupProduct(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeGroupProduct());
    }


    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-groups-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Create groups products",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change element and arrays to create groups products",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="products",
     *              @OA\Items(
     *
     *               @OA\Property(
     *                    property="sku",
     *                    title="Sku",
     *                    type="string",
     *                    example="12weer2",
     *               ),
     *               @OA\Property(
     *                    property="short_title",
     *                    title="Short title",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="characteristic_value",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristic_color_value",
     *
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *
     *               ),
     *               @OA\Property(
     *                     property="files",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          )
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="base_characteristic_value",
     *
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="is_color",
     *                              title="is_color",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *
     *               ),
     *              )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeGroupsProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeGroupsProduct($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-products-all",
     *      tags={"Nomenclature"},
     *      summary="Get products all",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexProductsAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexProductsAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-services-all",
     *      tags={"Nomenclature"},
     *      summary="Get services all",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Services",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexServicesAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexServicesAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-kits-all",
     *      tags={"Nomenclature"},
     *      summary="Get services all",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get kits in category id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                        property="total_page",
     *                        title="total_page",
     *                        example=1,
     *                        type="int"
     *                  ),
     *                  @OA\Property(
     *                        property="total",
     *                        title="total",
     *                        example=1,
     *                        type="int"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexKitsAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexKitsAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-not-actual-nomenclatures-all",
     *      tags={"Nomenclature"},
     *      summary="Get not actual nomenclatures all",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Services",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexNotActualNomenclaturesAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexNotActualNomenclaturesAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get product id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->showProduct($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-kit/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get kit id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showKit($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->showKit($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-service/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get service id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function showService($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->showService($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/outVisibility",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature out Visibility",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function toVisibility(): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->toVisibility());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/toVisibility",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature out Visibility",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function outVisibility(): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->outVisibility());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/change-min_price-nomenclature/{id}",
     *      tags={"Nomenclature"},
     *      summary="change price Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change min price value",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="value",
     *                    title="Visible",
     *                    type="number",
     *                    example=900,
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function changeMinPriceNomenclature($domain, $id): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->changeMinPriceNomenclature($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/change-price-nomenclature",
     *      tags={"Nomenclature"},
     *      summary="change price Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change id and price value",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="id",
     *                    title="Visible",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="value",
     *                    title="Visible",
     *                    type="number",
     *                    example=900,
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function changePriceNomenclature(): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->changePriceNomenclature());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/change-price-nomenclature-history",
     *      tags={"Nomenclature"},
     *      summary="change price Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change id and price value",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="id",
     *                    title="Visible",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="value",
     *                    title="Visible",
     *                    type="number",
     *                    example=900,
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function changePriceNomenclatureHistory(): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->changePriceNomenclatureHistory());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/choose-price-nomenclature",
     *      tags={"Nomenclature"},
     *      summary="change price Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change id and price value",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="nomenclature_id",
     *                    title="nomenclature id",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="value",
     *                    title="value",
     *                    type="number",
     *                    example=900,
     *               ),
     *               @OA\Property(
     *                    property="name_price",
     *                    title="name price",
     *                    type="number",
     *                    example="price_1",
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function choosePriceNomenclature(): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->choosePriceNomenclature());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/move-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Nomenclatures move in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="ID",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function moveProducts($domain, $id): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->moveProducts($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/move-services/{id}",
     *      tags={"Nomenclature"},
     *      summary="Services move in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="ID",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function moveServices($domain, $id): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->moveServices($id));
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/move-kits/{id}",
     *      tags={"Nomenclature"},
     *      summary="Nomenclatures move in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="ID",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function moveKits($domain, $id): JsonResponse
    {

        return $this->successResponse($this->nomenclatureService->moveKits($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/toArchive",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature To Archive",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */


    public function toArchive(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->toArchive());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/outArchive",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature out Archive",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function outArchive(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->outArchive());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/toActual",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature to Actual",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function toActual(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->toActual());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/outActual",
     *      tags={"Nomenclature"},
     *      summary="Nomenclature out Actual",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array id nomenclatures",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="nomenclatures",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=0,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function outActual(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->outActual());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/product/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */


    public function updateProductsHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateProductsHeader());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/service/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */


    public function updateServicesHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateServicesHeader());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/kit/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */


    public function updateKitsHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateKitsHeader());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/nomenclature/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateNomenclaturesHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateNomenclaturesHeader());
    }

    //validation

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-async-products-validations",
     *      tags={"Nomenclature"},
     *      summary="Validate create product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="validate",
     *                          @OA\Property(
     *                              property="key_validate",
     *                              title="key validate",
     *                              example="title",
     *                              type="string"
     *                          ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeAsyncProductsValidations(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeAsyncProductsValidations());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/update-async-products-validations",
     *      tags={"Nomenclature"},
     *      summary="Update Visiibility Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="validate",
     *                       @OA\Property(
     *                           property="key_validate",
     *                           title="key validate",
     *                           example="title",
     *                           type="string"
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateAsyncProductsValidations(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateAsyncProductsValidations());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/store-async-services-validations",
     *      tags={"Nomenclature"},
     *      summary="Validate create product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="validate",
     *                          @OA\Property(
     *                              property="key_validate",
     *                              title="key validate",
     *                              example="title",
     *                              type="string"
     *                          ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeAsyncServicesValidations(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeAsyncServicesValidations());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/update-async-services-validations",
     *      tags={"Nomenclature"},
     *      summary="Update Visiibility Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    property="id",
     *                    title="ID",
     *                    example=1,
     *                    type="int"
     *               ),
     *               @OA\Property(
     *                    property="validate",
     *                       @OA\Property(
     *                           property="key_validate",
     *                           title="key validate",
     *                           example="title",
     *                           type="string"
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateAsyncServicesValidations(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateAsyncServicesValidations());
    }



    /**
     * @OA\Post(
     *      path="/products/nomenclatures/generate",
     *      tags={"Nomenclature"},
     *      summary="Generate Products",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ResponseGenerateProducts"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function generateProducts(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->generateProducts());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/select-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Select product id, will give the leftovers in all warehouses",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="warehouse",
     *                          title="warehouse",
     *                          type="string",
     *                          example="warehouse",
     *                      ),
     *                      @OA\Property(
     *                          property="unit",
     *                          title="unit",
     *                          type="string",
     *                          example="unit",
     *                      ),
     *                      @OA\Property(
     *                          property="remainder",
     *                          title="Remainder",
     *                          type="int",
     *                          example=0,
     *                      ),
     *                       @OA\Property(
     *                          property="reserve",
     *                          title="Reserve",
     *                          type="int",
     *                          example=0,
     *                      ),
     *                      @OA\Property(
     *                          property="balance",
     *                          title="Balance",
     *                          type="int",
     *                          example=0,
     *                      ),
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectProduct($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get products when choosing a category in document",
     *      security={},
     *      @OA\Parameter(
     *         name="warehouse_id",
     *         description="Warehouse Id",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-services/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get services when choosing a category in document",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get services when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectServices($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectServices($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-products-all",
     *      tags={"Nomenclature"},
     *      summary="Get products all in document",
     *      security={},
     *      @OA\Parameter(
     *         name="warehouse_id",
     *         description="Warehouse Id",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectProductsAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectProductsAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-services-all",
     *      tags={"Nomenclature"},
     *      summary="Get services all in document",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectServicesAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectServicesAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/search-products-document-table",
     *      tags={"Nomenclature"},
     *      summary="Get products when choosing a category in document",
     *      security={},
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=true,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *         name="category_id",
     *         description="Category ID",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="total",
     *                    title="Total Data",
     *                    type="integer",
     *                  ),
     *                  @OA\Property(
     *                    property="total_page",
     *                    title="Total Page",
     *                    type="integer",
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function searchProductsDocumentTable(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->searchProductsDocumentTable());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/document/search-products",
     *      tags={"Nomenclature"},
     *      summary="Search title products",
     *      security={},
     *      @OA\Parameter(
     *         name="page",
     *         description="Page Number",
     *         required=false,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="s",
     *           description="Search",
     *           required=false,
     *           in="query",
     *           @OA\Schema(
     *                type="string"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get List Directories",
     *                   @OA\Items(
     *                 @OA\Property(
     *                    property="nomenclature_id",
     *                    title="Nomenclature id",
     *                    type="integer",
     *
     *                  ),
     *                  @OA\Property(
     *                    property="short_title",
     *                    title="Short Title",
     *                    type="string",
     *                  ),
     *                  )
     *              ),
     *               @OA\Property(
     *                 property="total",
     *                 title="total",
     *                 type="integer",
     *               ),
     *               @OA\Property(
     *                 property="total_page",
     *                 title="total page",
     *                 type="integer",
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function searchProductsDocument(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->searchProductsDocument());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/document/table-stocks-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get product with stocks table",
     *      security={},
     *      @OA\Parameter(
     *         name="price_id",
     *         description="Price ID",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *       @OA\Parameter(
     *           name="date",
     *           description="Date",
     *           required=true,
     *           in="query",
     *             example="2021-01-20 11:59:01",
     *           @OA\Schema(
     *                type="date"
     *           )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    ref="#/components/schemas/Directories"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function tableStocksProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->tableStocksProduct($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/document/table-write-of-stocks-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get product with stocks table",
     *      security={},
     *      @OA\Parameter(
     *         name="warehouse_id",
     *         description="Warehouse ID",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *          )
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    ref="#/components/schemas/Directories"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function tableWriteOfStocksProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->tableWriteOfStocksProduct($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-nomenclatures",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures to add",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                    ),
     *              ),
     *              @OA\Property(
     *                    property="price_id",
     *                    title="price ID",
     *                    example=1,
     *                    type="int"
     *              ),
     *              @OA\Property(
     *                    property="warehouse_id",
     *                    title="warehouse ID",
     *                    example=1,
     *                    type="int"
     *              ),
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedNomenclatures(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedNomenclatures());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-orders-nomenclatures",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures to add",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                    ),
     *              ),
     *              @OA\Property(
     *                    property="price_id",
     *                    title="price ID",
     *                    example=1,
     *                    type="int"
     *              ),
     *              @OA\Property(
     *                    property="warehouse_id",
     *                    title="warehouse ID",
     *                    example=1,
     *                    type="int"
     *              ),
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedOrdersNomenclatures(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedOrdersNomenclatures());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-services",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures to add",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                    ),
     *              ),
     *              @OA\Property(
     *                    property="price_id",
     *                    title="price ID",
     *                    example=1,
     *                    type="int"
     *              ),
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *               )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedServices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedServices());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-write-of-nomenclatures",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures to add",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="nomenclatures",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                    ),
     *              ),
     *              @OA\Property(
     *                    property="warehouse_id",
     *                    title="price ID",
     *                    example=1,
     *                    type="int"
     *              )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedWriteOfNomenclatures(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedWriteOfNomenclatures());
    }

    //related

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-related-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get Related Products in nomenclature id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in nomenclature id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getRelatedProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getRelatedProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-products-in-related-or-analog/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get Products related in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in category id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getProductsInRelatedOrAnalog($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getProductsInRelatedOrAnalog($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-products-in-related-or-analog-all",
     *      tags={"Nomenclature"},
     *      summary="Get Products In Related All",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products In Related All",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getProductsInRelatedOrAnalogAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getProductsInRelatedOrAnalogAll());
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-table-analog-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table analog Products in nomenclature id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Analog Products in nomenclature id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getTableAnalogProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getTableAnalogProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-table-related-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table related Products in nomenclature id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in nomenclature id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getTableRelatedProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getTableRelatedProducts($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/create-related-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function createRelatedProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->createRelatedProducts($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/delete-related-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function deleteRelatedProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->deleteRelatedProducts($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/create-analog-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function createAnalogProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->createAnalogProducts($id));
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/delete-analog-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function deleteAnalogProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->deleteAnalogProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/related/table-product/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table related Product in nomenclature id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in nomenclature id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getTableRelatedProduct($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getTableRelatedProduct($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-related-products/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table related Products in category id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in category id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectRelatedProducts($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectRelatedProducts($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-groups-nomenclatures/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table groups products in nomenclature id",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get table groups products in nomenclature id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getGroupsNomenclatures($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getGroupsNomenclatures($id));
    }

    /**
     * @OA\Get(
     *      path="/products/nomenclatures/get-select-related-products-all",
     *      tags={"Nomenclature"},
     *      summary="Get table related Products all",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Related Products in category id",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function indexSelectRelatedProductsAll(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->indexSelectRelatedProductsAll());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-kits",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="array kits",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedKits(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedKits());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-characteristics-kits",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Characteristics",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Characteristics"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedCharacteristicsKits(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedCharacteristicsKits());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-related-or-analog-nomenclatures",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedRelatedOrAnalogNomenclatures(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedRelatedOrAnalogNomenclatures());
    }

    /**
     * @OA\Post(
     *      path="/products/nomenclatures/selected-related_nomenclatures-in-nomenclature",
     *      tags={"Nomenclature"},
     *      summary="Selected nomenclatures id",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change nomenclatures id",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          )
     *                  ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function selectedRelatedNomenclaturesInNomenclature(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->selectedRelatedNomenclaturesInNomenclature());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/related-product/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateRelatedProductsHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateRelatedProductsHeader());
    }

    /**
     * @OA\Put(
     *      path="/products/nomenclatures/select-related-product/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateSelectRelatedProductsHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateSelectRelatedProductsHeader());
    }

    //price

    /**
     * @OA\Get(
     *      path="/products/prices/get-nomenclature-crm-prices",
     *      tags={"Nomenclature"},
     *      summary="Get table prices in CRM",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getNomenclatureCrmPrices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getNomenclatureCrmPrices());
    }



    /**
     * @OA\Get(
     *      path="/products/prices/get-nomenclature-prices",
     *      tags={"Nomenclature"},
     *      summary="Get table prices in nomenclatures",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getNomenclaturePrices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getNomenclaturePrices());
    }

    /**
     * @OA\Get(
     *      path="/products/prices/get-nomenclature-price/{id}",
     *      tags={"Nomenclature"},
     *      summary="Get table prices in CRM",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get products when choosing a category in document",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getNomenclaturePrice($domain, $id): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getNomenclaturePrice($id));
    }

    /**
     * @OA\Put(
     *      path="/products/prices/nomenclature-prices/headers",
     *      tags={"Nomenclature"},
     *      summary="Update Headers",
     *      @OA\RequestBody(
     *          description="Update Headers",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                    type="array",
     *                    collectionFormat="multi",
     *                    property="data",
     *                    @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              title="Id",
     *                              example=1,
     *                              type="int"
     *                          ),
     *                          @OA\Property(
     *                              property="is_visible",
     *                              title="is_visible",
     *                              example=true,
     *                              type="boolean"
     *                          ),
     *                          @OA\Property(
     *                              property="order",
     *                              title="Order",
     *                              example=0,
     *                              type="integer"
     *                          ),
     *               ),
     *            )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Data",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/ProductsHeader"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function updateNomenclaturePriceHeader(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->updateNomenclaturePriceHeader());
    }

    /**
     * @OA\Post(
     *      path="/products/prices/nomenclatures/deletePrices",
     *      tags={"Nomenclature"},
     *      summary="delete price",
     *      security={},
     *      @OA\RequestBody(
     *          description="delete price",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="data",
     *                  @OA\Items(
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer",
     *                          example=1,
     *                     ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function deletePrices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->deletePrices());
    }

    /**
     * @OA\Post(
     *      path="/products/prices/table-setting-prices",
     *      tags={"Nomenclature"},
     *      summary="Enter parameters to get a table with setting prices",
     *      @OA\RequestBody(
     *          description="Enter parameters to get a table with setting prices",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *                     property="type_prices",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *
     *               ),
     *               @OA\Property(
     *                     property="option_prices",
     *                         @OA\Property(
     *                              property="show_current_price",
     *                              type="boolean",
     *                              example=true,
     *                          ),
     *                          @OA\Property(
     *                              property="show_variance_price",
     *                              type="boolean",
     *                              example=true,
     *                          ),
     *                          @OA\Property(
     *                              property="show_dependent_price",
     *                              type="boolean",
     *                              example=true,
     *                          ),
     *                          @OA\Property(
     *                              property="show_min_price",
     *                              type="boolean",
     *                              example=true,
     *                          ),
     *               ),
     *              @OA\Property(
     *                   property="selection_nomenclature",
     *                   type="string",
     *                   example="documents || categories || type_prices || warehouses || nomenclatures",
     *                ),
     *               @OA\Property(
     *                     property="data",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get a table with setting prices",
     *                 @OA\Property(
     *                    property="headers",
     *                    title="Get Table Data Header",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/DirectoriesHeader"
     *                    )
     *                  ),
     *                  @OA\Property(
     *                    property="body",
     *                    title="Get Table Data Body",
     *                    type="array",
     *                    collectionFormat="multi",
     *                    @OA\Items(
     *                         ref="#/components/schemas/Directories"
     *                    )
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function getTableSettingPrices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->getTableSettingPrices());
    }

    /**
     * @OA\Post(
     *      path="/products/prices/store-setting-prices",
     *      tags={"Nomenclature"},
     *      summary="Change element and arrays to create products",
     *      @OA\RequestBody(
     *          description="Change date and arrays nomenclatures for create prices",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                       property="date",
     *                       type="date",
     *                       example="2020-12-16 17:04",
     *
     *               ),
     *               @OA\Property(
     *                     property="nomenclatures",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="min_price",
     *                              type="integer",
     *                              example=100,
     *                          ),
     *                          @OA\Property(
     *                              property="prices",
     *                              @OA\Items(
     *                                  @OA\Property(
     *                                      property="id",
     *                                      type="integer",
     *                                      example=1,
     *                                  ),
     *                                  @OA\Property(
     *                                      property="value",
     *                                      type="integer",
     *                                      example=100,
     *                              ),
     *
     *                             ),
     *                          ),
     *
     *                       ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Products",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Properties"
     *                 )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function storeSettingPrices(): JsonResponse
    {
        return $this->successResponse($this->nomenclatureService->storeSettingPrices());
    }
}
