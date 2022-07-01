<?php

namespace App\Characteristics\Controllers;

use App\Characteristics\Repositories\CharacteristicRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CharacteristicController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $characteristicRepository;

    public function __construct(CharacteristicRepository $characteristicRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->characteristicRepository = $characteristicRepository;
    }

    public function index(): JsonResponse
    {
        $characteristic_color = ['characteristic_color' => $this->characteristicRepository->getCharacteristicColor()];
        $characteristic_size = ['characteristic_size' => $this->characteristicRepository->getCharacteristicSize()];
        $characteristics = ['characteristics' => $this->characteristicRepository->getCharacteristicsCustom()];
        return $this->successResponse([$characteristic_color, $characteristic_size, $characteristics], Response::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:characteristics|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->characteristicRepository->incrementCharacteristic();
            $characteristic = $this->characteristicRepository->createCharacteristic($request->title);
            if (isset($request->values)) {
                $characteristic_value = $this->characteristicRepository->createCharacteristicValue($characteristic->id, $request->values);
                $characteristic->characteristic_value = $characteristic_value;
            }else{
                $characteristic->characteristic_value = [];
            }
            return $this->successResponse($characteristic, Response::HTTP_CREATED);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|unique:characteristics,title,' . $id . 'id|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $this->characteristicRepository->updateCharacteristic($id, $request->title);
            $this->characteristicRepository->updateCharacteristicValue($id, $request->values ?? []);
            $characteristic = $this->characteristicRepository->getCharacteristic($id);

            return $this->successResponse($characteristic, Response::HTTP_CREATED);
        }
    }

    public function edit($id): JsonResponse
    {
        $characteristic = $this->characteristicRepository->getCharacteristic($id);
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function editColor($id = 1): JsonResponse
    {
        $characteristic = $this->characteristicRepository->getCharacteristicColor();
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function editSize($id = 2): JsonResponse
    {
        $characteristic = $this->characteristicRepository->getCharacteristicSize();
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function updateColor(Request $request, $id = 1): JsonResponse
    {
        //file manager
//        if (base64_decode($request->selectValue, true)) {
//            echo 'TRUE';
//        } else {
//            echo 'FALSE';
//        }
//
//        $image = $request->selectValue;  // your base64 encoded
//        $image = str_replace('data:image/png;base64,', '', $image);
//        $image = str_replace(' ', '+', $image);
//        $imageName = Str::random(10) . '.png';
//        Storage::disk('public')->put($imageName, base64_decode($image));
//
//        dd($image);
        $this->characteristicRepository->updateCharacteristicColorValue($id, $request->values ?? null);
        $characteristic = $this->characteristicRepository->getCharacteristicColor();
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function updateSize(Request $request, $id = 2): JsonResponse
    {

        $this->characteristicRepository->updateCharacteristicSizeValue($id, $request->values ?? null);
        $characteristic = $this->characteristicRepository->getCharacteristicSize();
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function sortCharacteristics(Request $request): JsonResponse
    {
        $this->characteristicRepository->sortCharacteristics($request->characteristics);
        $characteristics = $this->characteristicRepository->getCharacteristics();
        return $this->successResponse($characteristics, Response::HTTP_CREATED);
    }

    public function getCharacteristicsCategory($id): JsonResponse
    {
        $characteristics = $this->characteristicRepository->getCharacteristicsCategory($id);
        return $this->successResponse($characteristics, Response::HTTP_CREATED);
    }

    public function addCharacteristicValue(Request $request, $id): JsonResponse
    {
        $characteristic = $this->characteristicRepository->addCharacteristicValue($id, $request->title);
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function addCharacteristicColorValue(Request $request, $id = 1): JsonResponse
    {
        $characteristic = $this->characteristicRepository->addCharacteristicColorValue($id, $request->all());
        return $this->successResponse($characteristic, Response::HTTP_CREATED);
    }

    public function toArchive(Request $request): JsonResponse
    {
        $this->characteristicRepository->toArchive($request->items);
        $items = $this->characteristicRepository->getCharacteristics();
        return $this->successResponse($items, Response::HTTP_CREATED);
    }
}
