<?php

namespace App\Suppliers\Controllers;

use App\Suppliers\Repositories\SupplierGroupRepository;
use App\Suppliers\Repositories\SupplierRepository;
use App\Suppliers\Repositories\SupplierHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $supplierRepository, $supplierHeaderRepository, $supplierGroupRepository;

    public function __construct(SupplierRepository $supplierRepository, SupplierHeaderRepository $supplierHeaderRepository, SupplierGroupRepository $supplierGroupRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        app()->setLocale(request()->lang);

        //Repository
        $this->supplierRepository = $supplierRepository;
        $this->supplierGroupRepository = $supplierGroupRepository;
        $this->supplierHeaderRepository = $supplierHeaderRepository;
    }

    public function getTable(Request $request): JsonResponse
    {
        $group = $this->supplierGroupRepository->getItem($request->group_id);
        if(isset($group)){
            $group_ids = $group->getDescendants($group);
        }else{
            $group_ids = null;
        }

        $table = $this->supplierRepository->getItemsTable($group_ids);
        $tableHeader = $this->supplierHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $tableHeader,
            'body'    => $table['data'],
            'total_page'   => $table['last_page'],
            'total'   => $table['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->supplierRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'title_supplier' => isset($data['title_supplier']) ? ['required', 'string', 'max:255', 'unique:suppliers'] : [],
            'title_company' => isset($data['title_company']) ? ['required', 'string', 'max:255', 'unique:suppliers'] : [],
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        }

        $item = $this->supplierRepository->createItem();
        $this->supplierRepository->createOrUpdateActualAddress($item->id);
        $this->supplierRepository->createOrUpdateDeliveryAddress($item->id);
        $this->supplierRepository->createOrUpdateContactsAndDocuments($item->id);
        $this->supplierRepository->createOrUpdateCells($item->id);

        return $this->successResponse($this->supplierRepository->getItem($item->id));
    }

   public function update(Request $request, $id)
   {
          $item = $this->supplierRepository->updateItem($id);
          $this->supplierRepository->createOrUpdateActualAddress($item->id);
          $this->supplierRepository->createOrUpdateDeliveryAddress($item->id);
          $this->supplierRepository->createOrUpdateContactsAndDocuments($item->id);
          $this->supplierRepository->createOrUpdateCells($item->id);

          return $this->successResponse($this->supplierRepository->getItem($item->id));
   }

    public function changeGroups(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->supplierRepository->updateGroups();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->supplierHeaderRepository->updateHeaders();

        return $this->successResponse($this->supplierHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->supplierRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function storeAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $table = 'suppliers';
            switch ($key) {
                case 'phone':
                    $table = 'supplier_contacts';
                    break;
            }
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'unique:' . $table . ',' . $key . '|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }

    public function updateAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $table = 'suppliers';
            switch ($key) {
                case 'phone':
                    $table = 'supplier_contacts';
                    break;
            }

            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'unique:' . $table . ',' . $key . ',' . $request->id . 'id|max:255',
                ]
            );
        }

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_OK);
        } else {
            return $this->successMessage('true', Response::HTTP_CREATED);
        }
    }
}
