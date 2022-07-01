<?php

namespace App\Categories\Controllers;

use App\Categories\Repositories\CategoryRepository;
use App\Characteristics\Repositories\CharacteristicRepository;
use App\Core\Traits\ApiResponder;
use App\Directories\Repositories\Units\UnitRepository;
use App\Properties\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $categoryRepository;
    private $characteristicRepository;
    private $propertyRepository;
    private $unitRepository;

    public function __construct(CategoryRepository $categoryRepository, CharacteristicRepository $characteristicRepository, PropertyRepository $propertyRepository, UnitRepository $unitRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->categoryRepository = $categoryRepository;
        $this->characteristicRepository = $characteristicRepository;
        $this->propertyRepository = $propertyRepository;
        $this->unitRepository = $unitRepository;
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->getCategories();
        return $this->successResponse($categories, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:categories|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $unit_default_value = $this->unitRepository->getDefaultValue();
            $this->categoryRepository->incrementCategory();
            $category = $this->categoryRepository->createCategory($request->all(), $unit_default_value);
            $category_full = $this->categoryRepository->getCategoryWithChildren($category->id);
            return $this->successResponse($category_full, Response::HTTP_CREATED);
        }

    }

    public function create(): JsonResponse
    {
        $categories = (['categories' => $this->categoryRepository->getCategories()]);
        $characteristics = (['characteristics' => $this->characteristicRepository->getCharacteristicsWithout()]);
        $properties = (['properties' => $this->propertyRepository->getPropertiesWithout()]);
        return $this->successResponse([$categories, $characteristics, $properties], Response::HTTP_OK);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:categories,title,' . $id . 'id|max:255'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $category = $this->categoryRepository->updateCategory($id, $request->all());

            return $this->successResponse($category, Response::HTTP_CREATED);
        }
    }

    public function show($id): JsonResponse
    {
        $category = $this->categoryRepository->getCategory($id);
        return $this->successResponse($category, Response::HTTP_OK);
    }

    public function edit($id): JsonResponse
    {
        $category = (['category' => $this->categoryRepository->getCategoryWithChildren($id)]);
        $categories = (['categories' => $this->categoryRepository->getCategories()]);
        $characteristics = (['characteristics' => $this->characteristicRepository->getCharacteristicsWithout()]);
        $properties = (['properties' => $this->propertyRepository->getPropertiesWithout()]);
        return $this->successResponse([$category ,$categories, $characteristics, $properties], Response::HTTP_OK);
    }

    public function sortCategories(Request $request): JsonResponse
    {
        $this->categoryRepository->parentCategories($request->categories);
        $categories = $this->categoryRepository->getCategories();

        return $this->successResponse($categories, Response::HTTP_CREATED);
    }

    public function sortItemCategories(Request $request): JsonResponse
    {
        $this->categoryRepository->sortItemCategories($request->categories);
        $categories = $this->categoryRepository->getCategories();

        return $this->successResponse($categories, Response::HTTP_CREATED);
    }

    public function visibility(Request $request, $id): JsonResponse
    {
        $category = $this->categoryRepository->visibilityCategory($id, $request->all());
        return $this->successResponse($category, Response::HTTP_CREATED);
    }

    public function toArchive(Request $request): JsonResponse
    {
        foreach ($request->categories as $key => $cat) {
            $categories = $this->categoryRepository->getCategoryChildren($cat['id']);
            $categories_id[] = $this->recursiveCategories($categories);
        }
        $ids = [];
        foreach ($categories_id as $items) {
            foreach ($items as $item){
                $ids[]['id'] =  $item;
            }
        }
        $this->categoryRepository->toArchive($ids);
        $categories = $this->categoryRepository->getCategories();
        return $this->successResponse($categories, Response::HTTP_CREATED);
    }

    public function storeAsyncValidations(Request $request)
    {

        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:categories|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successResponse('true', Response::HTTP_CREATED);
        }
    }

    public function updateAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:categories,' . $key . ',' . $request->id . 'id|max:255',
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

    //times method
    public function recursiveCategories($categories)
    {
        if (isset($categories->id)) {
            $category_id[] = $categories->id;
            foreach ($categories->children as $category) {
                if (isset($category->id)) {
                    $category_id[] = $category->id;
                    if ($category->children->count() > 0) {
                        $this->recursiveCategories($category->children);
                    }
                }
            }
            return $category_id;
        }
    }
}
