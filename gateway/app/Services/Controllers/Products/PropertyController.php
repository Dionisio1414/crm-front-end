<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\PropertyService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    use ApiResponder;

    public $propertyService;

    /**
     * Create a new controller instance.
     *
     * @param PropertyService $propertyService
     */
    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    /**
     * @OA\Get(
     *      path="/products/properties",
     *      tags={"Property"},
     *      summary="Get All Properties",
     *      security={},
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

    public function index(): JsonResponse
    {
        return $this->successResponse($this->propertyService->getProperties());
    }

    /**
     * @OA\Post(
     *      path="/products/properties",
     *      tags={"Property"},
     *      summary="Add Properties",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
     *                    type="string",
     *                    example="title",
     *               ),
     *              @OA\Property(
     *                 property="values",
     *                 type="string",
     *                 example={"test1", "test2"},
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/GetProperty"
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
        return $this->successResponse($this->propertyService->storeProperty());
    }

    /**
     * @OA\Put(
     *      path="/products/properties/{id}",
     *      tags={"Property"},
     *      summary="Update Proprety",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
     *                    type="string",
     *                    example="title",
     *                  ),
     *                  @OA\Property(
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
     *                     )
     *                  )
     *              )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get Property",
     *                 ref="#/components/schemas/GetPropertyWithCategories"
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

        return $this->successResponse($this->propertyService->editProperty($id));
    }

    /**
     * @OA\Get(
     *      path="/products/properties/{id}/edit",
     *      tags={"Property"},
     *      summary="Get property for editing",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get property for editing",
     *                 ref="#/components/schemas/GetPropertyWithCategories"
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
        return $this->successResponse($this->propertyService->getProperty($id));
    }

    /**
     * @OA\Post(
     *      path="/products/properties/sortProperties",
     *      tags={"Property"},
     *      summary="Update Sort Propreties",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="properties",
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

    public function sortProperties(): JsonResponse
    {
        return $this->successResponse($this->propertyService->sortProperties());
    }

    /**
     * @OA\Post(
     *      path="/products/properties/toArchive",
     *      tags={"Property"},
     *      summary="Propreties To Archive",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="properties",
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

    public function toArchive(): JsonResponse
    {
        return $this->successResponse($this->propertyService->toArchive());
    }

    /**
     * @OA\Get(
     *      path="/products/properties/{id}/properties-category",
     *      tags={"Property"},
     *      summary="Get properties in category",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Properties in category",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/PropertiesCategory"
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

    public function getPropertiesCategory($domain, $id): JsonResponse
    {
        return $this->successResponse($this->propertyService->getPropertiesCategory($id));
    }

    /**
     * @OA\Post(
     *      path="/products/properties/{id}/add-property-value",
     *      tags={"Property"},
     *      summary="Add propery value in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
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
     *                 ref="#/components/schemas/PropertyValue"
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

    public function addPropertyValue($domain, $id): JsonResponse
    {
        return $this->successResponse($this->propertyService->addPropertyValue($id));
    }

    /**
     * @OA\Put(
     *      path="/products/properties/{id}/edit-property-value",
     *      tags={"Property"},
     *      summary="Edit propery value in category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Properties",
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
     *                 ref="#/components/schemas/PropertyValue"
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

    public function editPropertyValue($domain, $id): JsonResponse
    {
        return $this->successResponse($this->propertyService->editPropertyValue($id));
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
