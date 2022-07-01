<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\CharacteristicService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacteristicController extends Controller
{
    use ApiResponder;

    public $characteristicService;

    /**
     * Create a new controller instance.
     *
     * @param CharacteristicService $characteristicService
     */
    public function __construct(CharacteristicService $characteristicService)
    {
        $this->characteristicService = $characteristicService;
    }

    /**
     * @OA\Get(
     *      path="/products/characteristics",
     *      tags={"Characteristic"},
     *      summary="Get All Characteristics",
     *      security={},
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
     *                    @OA\Property(
     *                      property="characteristic_color",
     *                      ref="#/components/schemas/CharacteristicColor"
     *                     ),
     *                     @OA\Property(
     *                      property="characteristic_size",
     *                      ref="#/components/schemas/CharacteristicSize"
     *                     ),
     *                     @OA\Property(
     *                      property="сharacteristics",
     *                      type="array",
     *                      collectionFormat="multi",
     *                          @OA\Items(
     *                              ref="#/components/schemas/Characteristics"
     *                          ),
     *                     ),
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

    public function index(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->getCharacteristics());
    }

    /**
     * @OA\Post(
     *      path="/products/characteristics",
     *      tags={"Characteristic"},
     *      summary="Add Characteristic",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Characteristic",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="values",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="title",
     *                              type="string",
     *                              example="title",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string",
     *                              example="code",
     *                          ),
     *                          @OA\Property(
     *                              property="edit",
     *                              type="int",
     *                              example=1,
     *                          ),
     *                     ),
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Characteristic",
     *                     ref="#/components/schemas/GetCharacteristic"
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

    public function store()
    {
        return $this->successResponse($this->characteristicService->createCharacteristic());
    }

    /**
     * @OA\Put(
     *      path="/products/characteristics/{id}",
     *      tags={"Characteristic"},
     *      summary="Update Characteristic",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Characteristic",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                     property="values",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="int",
     *                              example="if new value, id = null",
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              type="string",
     *                              example="title",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string",
     *                              example="code",
     *                          ),
     *                          @OA\Property(
     *                              property="edit",
     *                              type="int",
     *                              example=1,
     *                          ),
     *                     ),
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Characteristic",
     *                     ref="#/components/schemas/Characteristic"
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

    public function update($domain, $id): JsonResponse
    {
        return $this->successResponse($this->characteristicService->editCharacteristic($id));
    }

    /**
     * @OA\Put(
     *      path="/products/characteristics/updateColorCharacteristics",
     *      tags={"Characteristic"},
     *      summary="Update ColorCharacteristics",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                     property="values",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="int",
     *                              example="if new value, id = null",
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              type="string",
     *                              example="title",
     *                          ),
     *                          @OA\Property(
     *                              property="code",
     *                              type="string",
     *                              example="code",
     *                          ),
     *                          @OA\Property(
     *                              property="selectValue",
     *                              type="string",
     *                              example="#111 or image_id",
     *                          ),
     *                     ),
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Characteristic",
     *                     ref="#/components/schemas/CharacteristicColor"
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


    public function updateColor($id = 1): JsonResponse
    {
        return $this->successResponse($this->characteristicService->editColorCharacteristic($id));
    }

    /**
     * @OA\Put(
     *      path="/products/characteristics/updateSizeCharacteristics",
     *      tags={"Characteristic"},
     *      summary="Update SizeCharacteristics",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                     property="values",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="int",
     *                              example="if new value, id = null",
     *                          ),
     *                          @OA\Property(
     *                              property="title",
     *                              type="string",
     *                              example="16",
     *                          ),
     *                          @OA\Property(
     *                              property="check",
     *                              type="int",
     *                              example=0,
     *                          ),
     *                          @OA\Property(
     *                              property="type",
     *                              type="string",
     *                              example="(0 - sizeNumber, 1 - sizeString)",
     *                          ),
     *                     ),
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Characteristic",
     *                     ref="#/components/schemas/CharacteristicSize"
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

    public function updateSize($id = 2): JsonResponse
    {
        return $this->successResponse($this->characteristicService->editSizeCharacteristic($id));
    }

    /**
     * @OA\Get(
     *      path="/products/characteristics/{id}/edit",
     *      tags={"Characteristic"},
     *      summary="Get characteristic for editing",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get characteristic for editing",
     *                 ref="#/components/schemas/Characteristic"
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

    public function edit($domain, $id): JsonResponse
    {
        return $this->successResponse($this->characteristicService->getCharacteristic($id));
    }

    /**
     * @OA\Get(
     *      path="/products/characteristics/editColorCharacteristics",
     *      tags={"Characteristic"},
     *      summary="Get characteristic color for editing",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                   property="data",
     *                   title="Get characteristic color for editing",
     *                   ref="#/components/schemas/CharacteristicColor"
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

    public function editColor(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->getColorCharacteristic());
    }

    /**
     * @OA\Get(
     *      path="/products/characteristics/editSizeCharacteristics",
     *      tags={"Characteristic"},
     *      summary="Get characteristic size for editing",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                   property="data",
     *                   title="Get characteristic size for editing",
     *                   ref="#/components/schemas/CharacteristicSize"
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


    public function editSize(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->getSizeCharacteristic());
    }

    /**
     * @OA\Post(
     *      path="/products/characteristics/sortCharacteristics",
     *      tags={"Characteristic"},
     *      summary="Update Sort Characteristics",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="characteristics",
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

    public function sortCharacteristics(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->sortCharacteristics());
    }

    /**
     * @OA\Get(
     *      path="/products/characteristics/{id}/characteristics-category",
     *      tags={"Characteristic"},
     *      summary="Get Characteristics in category",
     *      security={},
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
     *                     ref="#/components/schemas/CharacteristicsCategory"
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

    public function getCharacteristicsCategory($domain, $id): JsonResponse
    {
        return $this->successResponse($this->characteristicService->getCharacteristicsCategory($id));
    }

    /**
     * @OA\Post(
     *      path="/products/characteristics/{id}/add-characteristic-value",
     *      tags={"Characteristic"},
     *      summary="Add Characteristic value in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Characteristic",
     *                    type="string",
     *                    example="title",
     *                  )
     *              )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get property value",
     *                 ref="#/components/schemas/CharacteristicValue"
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

    public function addCharacteristicValue($domain, $id): JsonResponse
    {
        return $this->successResponse($this->characteristicService->addCharacteristicValue($id));
    }

    /**
     * @OA\Post(
     *      path="/products/characteristics/add-characteristic-color-value",
     *      tags={"Characteristic"},
     *      summary="Add Characteristic color value in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *                     @OA\Property(
     *                         property="title",
     *                         type="string",
     *                         example="title",
     *                     ),
     *                     @OA\Property(
     *                          property="code",
     *                          type="string",
     *                          example="code",
     *                     ),
     *                     @OA\Property(
     *                          property="color",
     *                          type="string",
     *                          example="#111",
     *                     ),
     *                     @OA\Property(
     *                          property="image_id",
     *                          type="intager",
     *                          example=1,
     *                     ),
     *              )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get property value",
     *                 ref="#/components/schemas/CharacteristicColorValue"
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

    public function addCharacteristicColorValue(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->addCharacteristicColorValue());
    }

    /**
     * @OA\Post(
     *      path="/products/characteristics/toArchive",
     *      tags={"Characteristic"},
     *      summary="Characteristics To Archive",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="items",
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
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Properties",
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

    public function toArchive(): JsonResponse
    {
        return $this->successResponse($this->characteristicService->toArchive());
    }


//    public function store(Request $request): JsonResponse
//    {
//        $this->authorService->getAuthor($request->author_id);
//
//        return $this->successResponse($this->bookService->createBook($request->all()));
//    }
//
//    public function show($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->getBook($book));
//    }
//
//    public function update(Request $request, $book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->editBook(($request->all()),
//            $book));
//    }
//
//    public function destroy($book): JsonResponse
//    {
//        return $this->successResponse($this->bookService->deleteBook($book));
//    }
}
