<?php

namespace App\Services\Controllers\Products;

use App\Core\Http\Controllers\Controller;
use App\Services\Service\CategoryService;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponder;

    public $categoryService;

    /**
     * Create a new controller instance.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * @OA\Get(
     *      path="/products/categories",
     *      tags={"Category"},
     *      summary="Get All Categories",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Categories"
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
        return $this->successResponse($this->categoryService->getCategories());
    }

    /**
     * @OA\Get(
     *      path="/products/categories/create",
     *      tags={"Category"},
     *      summary="Get Categories, Properties, Characteristic. Form for creating a category",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/Categories"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="characteristics",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/CharacteristicsWithout"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="properties",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/PropertiesWithout"
     *                          ),
     *                      )
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

    public function create(): JsonResponse
    {
        return $this->successResponse($this->categoryService->createCategory());
    }

    /**
     * @OA\Post(
     *      path="/products/categories",
     *      tags={"Category"},
     *      summary="Add Category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Category",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description Category",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="image_id",
     *                    title="Image",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="parent_id",
     *                    title="Parent category",
     *                    type="number",
     *                    example=1
     *               ),
     *               @OA\Property(
     *                    property="status",
     *                    title="Status",
     *                    type="number",
     *                    example=0,
     *               ),
     *              @OA\Property(
     *                    property="identifier_fullness",
     *                    title="Identifier fullness",
     *                    type="number",
     *                    example=100,
     *               ),
     *              @OA\Property(
     *                    property="identifier_successful",
     *                    title="Identifier successful",
     *                    type="number",
     *                    example=50,
     *               ),
     *              @OA\Property(
     *                    property="unit_id",
     *                    title="Unit id",
     *                    type="number",
     *                    example=1
     *               ),
     *               @OA\Property(
     *                     property="properties",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristics",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
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
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get category",
     *                     ref="#/components/schemas/Category"
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

    public function store(): JsonResponse
    {
        return $this->successResponse($this->categoryService->storeCategory());
    }

    /**
     * @OA\Put(
     *      path="/products/categories/{id}",
     *      tags={"Category"},
     *      summary="Update Category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="title",
     *                    title="Title Category",
     *                    type="string",
     *                    example="title",
     *               ),
     *               @OA\Property(
     *                    property="desc",
     *                    title="Description Category",
     *                    type="string",
     *                    example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua",
     *               ),
     *               @OA\Property(
     *                    property="image_id",
     *                    title="Image",
     *                    type="number",
     *                    example=1,
     *               ),
     *               @OA\Property(
     *                    property="parent_id",
     *                    title="Parent category",
     *                    type="number",
     *                    example=1
     *               ),
     *               @OA\Property(
     *                    property="status",
     *                    title="Status",
     *                    type="number",
     *                    example=0,
     *               ),
     *               @OA\Property(
     *                    property="unit_id",
     *                    title="Unit id",
     *                    type="number",
     *                    example=1
     *               ),
     *               @OA\Property(
     *                     property="properties",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
     *               ),
     *               @OA\Property(
     *                     property="characteristics",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                       ),
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
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get category",
     *                     ref="#/components/schemas/Category"
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
        return $this->successResponse($this->categoryService->editCategory($id));
    }

    /**
     * @OA\Get(
     *      path="/products/categories/{id}",
     *      tags={"Category"},
     *      summary="Get Category",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="User Info",
     *                    ref="#/components/schemas/CategoryWithParent"
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

    public function show($domain, $id): JsonResponse
    {
        return $this->successResponse($this->categoryService->getCategory($id));
    }

    /**
     * @OA\Get(
     *      path="/products/categories/{id}/edit",
     *      tags={"Category"},
     *      summary="Get Categories, Properties, Characteristic and selected category. Form for edit category",
     *      security={},
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                      @OA\Property(
     *                          property="category",
     *                               ref="#/components/schemas/Category"
     *                      ),
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/Categories"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="characteristics",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/CharacteristicsWithout"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="properties",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/PropertiesWithout"
     *                          ),
     *                      )
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


    public function edit($domain, $id): JsonResponse
    {

        return $this->successResponse($this->categoryService->getEditCategory($id));
    }

    /**
     * @OA\Post(
     *      path="/products/categories/sortCategories",
     *      tags={"Category"},
     *      summary="Sort categories",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change id and parent id",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                          @OA\Property(
     *                              property="parent_id",
     *                              type="integer",
     *                              example=0,
     *                          ),
     *                      ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/Categories"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="characteristics",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/CharacteristicsWithout"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="properties",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/PropertiesWithout"
     *                          ),
     *                      )
     *                 )
     *              )
     *               )
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

    public function sortCategories(): JsonResponse
    {

        return $this->successResponse($this->categoryService->sortCategories());
    }

    /**
     * @OA\Post(
     *      path="/products/categories/sortItemCategories",
     *      tags={"Category"},
     *      summary="Sort item categories",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change id and parent id",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                     property="categories",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="id",
     *                              type="integer",
     *                              example=1,
     *                          ),
     *                      ),
     *               ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                      @OA\Property(
     *                          property="categories",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/Categories"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="characteristics",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/CharacteristicsWithout"
     *                          ),
     *                      ),
     *                      @OA\Property(
     *                          property="properties",
     *                          type="array",
     *                          collectionFormat="multi",
     *                          @OA\Items(
     *                               ref="#/components/schemas/PropertiesWithout"
     *                          ),
     *                      )
     *                 )
     *              )
     *               )
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

    public function sortItemCategories(): JsonResponse
    {

        return $this->successResponse($this->categoryService->sortItemCategories());
    }

    /**
     * @OA\Put(
     *      path="/products/categories/{id}/visibility",
     *      tags={"Category"},
     *      summary="Update Visiibility Category",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array properties and characteristics",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *               @OA\Property(
     *                    property="status",
     *                    title="Status",
     *                    type="number",
     *                    example=0,
     *               )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="Get category",
     *                     ref="#/components/schemas/Category"
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

    public function visibility($domain, $id): JsonResponse
    {

        return $this->successResponse($this->categoryService->visibilityCategory($id));
    }

    /**
     * @OA\Post(
     *      path="/products/categories/toArchive",
     *      tags={"Category"},
     *      summary="Categories To Archive",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title, array values",
     *          description="Title",
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(
     *              property="categories",
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
     *                 title="All Categories",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Categories"
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
        return $this->successResponse($this->categoryService->toArchive());
    }

    /**
     * @OA\Post(
     *      path="/products/categories/store-async-validations",
     *      tags={"Category"},
     *      summary="Validate create product",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
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

    public function storeAsyncValidations(): JsonResponse
    {
        return $this->successResponse($this->categoryService->storeAsyncValidations());
    }

    /**
     * @OA\Post(
     *      path="/products/categories/update-async-validations",
     *      tags={"Category"},
     *      summary="Update Visiibility Nomenclature",
     *      security={},
     *      @OA\RequestBody(
     *          description="Change title",
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

    public function updateAsyncValidations(): JsonResponse
    {
        return $this->successResponse($this->categoryService->updateAsyncValidations());
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
