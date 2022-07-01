<?php

namespace App\Properties\Controllers;

use App\Properties\Repositories\PropertyRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class PropertyController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->propertyRepository = $propertyRepository;
    }

    public function index(): JsonResponse
    {

        $properties = $this->propertyRepository->getProperties();
        return $this->successResponse($properties, Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:properties|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->propertyRepository->incrementProperty();
            $property = $this->propertyRepository->createProperty($request->title);
            if (isset($request->values)) {
                $property_value = $this->propertyRepository->createPropertyValue($property->id, $request->values);
                $property->property_value = $property_value;
            }else{
                $property->property_value = [];
            }

            return $this->successResponse($property, Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:properties,title,' . $id . 'id|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->propertyRepository->updateProperty($id, $request->title);
            $this->propertyRepository->updatePropertyValue($id, $request->values ?? []);
            $property = $this->propertyRepository->getProperty($id);

            return $this->successResponse($property, Response::HTTP_CREATED);
        }
    }

    public function edit($id): JsonResponse
    {
        $property = $this->propertyRepository->getProperty($id);
        return $this->successResponse($property, Response::HTTP_CREATED);
    }

    public function sortProperties(Request $request): JsonResponse
    {

        $this->propertyRepository->sortProperties($request->properties);
        $properties = $this->propertyRepository->getProperties();

        return $this->successResponse($properties, Response::HTTP_CREATED);
    }

    public function toArchive(Request $request): JsonResponse
    {
        $this->propertyRepository->toArchive($request->properties);
        $properties = $this->propertyRepository->getProperties();
        return $this->successResponse($properties, Response::HTTP_CREATED);
    }

    public function getPropertiesCategory($id): JsonResponse
    {
        $properties = $this->propertyRepository->getPropertiesCategory($id);
        return $this->successResponse($properties, Response::HTTP_CREATED);
    }

    public function addPropertyValue(Request $request, $id): JsonResponse
    {
        $property = $this->propertyRepository->addPropertyValue($id, $request->title);
        return $this->successResponse($property, Response::HTTP_CREATED);
    }

    public function editPropertyValue(Request $request, $id): JsonResponse
    {
        $property = $this->propertyRepository->editPropertyValue($id, $request->title);
        return $this->successResponse($property, Response::HTTP_CREATED);
    }

//    public function store(Request $request) :JsonResponse
//    {
//        $rules = [
//            'title'       => 'required|max:255',
//            'description' => 'required|max:255',
//            'price'       => 'required|min:0',
//            'author_id'   => 'required|min:1',
//        ];
//        $this->validate($request, $rules);
//        $book = Book::create($request->all());
//
//        return $this->successResponse($book, Response::HTTP_CREATED);
//    }
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
