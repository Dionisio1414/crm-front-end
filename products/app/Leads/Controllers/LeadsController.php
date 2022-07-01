<?php

namespace App\Leads\Controllers;

use App\Leads\Repositories\LeadRepository;
use App\Leads\Repositories\LeadHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class LeadsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $leadRepository, $leadHeaderRepository;

    public function __construct(LeadRepository $leadRepository, LeadHeaderRepository $leadHeaderRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        app()->setLocale(request()->lang);

        //Repository
        $this->leadRepository = $leadRepository;
        $this->leadHeaderRepository = $leadHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $table = $this->leadRepository->getItemsTable();
        $tableHeader = $this->leadHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $tableHeader,
            'body' => $table['data'],
            'total_page' => $table['last_page'],
            'total' => $table['total']
        ];

        return $this->successResponse($rezult);
    }

    public function getFailureTable(): JsonResponse
    {
        $table = $this->leadRepository->getItemsFailureTable();
        $tableHeader = $this->leadHeaderRepository->getLeadsFailureHeaders();

        $rezult = (object)[
            'headers' => $tableHeader,
            'body' => $table['body'],
            'total_page' => $table['total_page'],
            'total' => $table['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->leadRepository->list();

        $rezult = (object)[
            'list' => $data['data'],
            'total_page' => $data['last_page'],
            'total' => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->leadRepository->createItem();

        if (isset($request->categories)) {
            $this->leadRepository->createOrUpdateItemCategories($item->id);
        }

        if (isset($request->nomenclatures)) {
            $this->leadRepository->createOrUpdateItemNomenclatures($item->id);
        }

        $this->leadRepository->createOrUpdateDelivery($item->id);
        $this->leadRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->leadRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->leadRepository->updateItem($id);

        if (isset($request->categories)) {
            $this->leadRepository->createOrUpdateItemCategories($item->id);
        }

        if (isset($request->nomenclatures)) {
            $this->leadRepository->createOrUpdateItemNomenclatures($item->id);
        }

        $this->leadRepository->createOrUpdateDelivery($item->id);
        $this->leadRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->leadRepository->getItem($item->id));
    }

    public function show($id)
    {
        $rezult = $this->leadRepository->getList($id);;

        return $this->successResponse($rezult);
    }

    public function edit($id)
    {
        $rezult = $this->leadRepository->getListEdit($id);;

        return $this->successResponse($rezult);
    }

    public function storeOrderAndCustomer($id)
    {
        $rezult = $this->leadRepository->getList($id);;


        return $this->successResponse($rezult);
    }

    public function updateHeader(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->leadHeaderRepository->updateHeaders();

        return $this->successResponse($this->leadHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->leadRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function outFailure(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->leadRepository->outFailure();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function toFailure(Request $request)
    {
        if (!$request->data) {
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->leadRepository->toFailure();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function storeAsyncValidations(Request $request)
    {

        foreach ($request->get('validate') as $key => $validate) {
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'required|unique:leads|max:255',
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
                    $key => 'required|unique:leads,' . $key . ',' . $request->id . 'id|max:255',
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
}
