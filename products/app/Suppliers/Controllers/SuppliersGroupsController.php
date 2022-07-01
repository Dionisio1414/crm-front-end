<?php

namespace App\Suppliers\Controllers;

use App\Suppliers\Repositories\SupplierGroupRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class SuppliersGroupsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $supplierGroupRepository;

    public function __construct(SupplierGroupRepository $supplierGroupRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        //Repository
        $this->supplierGroupRepository = $supplierGroupRepository;
    }

    public function index()
    {
        $data = $this->supplierGroupRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title' => 'required|nullable|unique:supplier_groups|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $group = $this->supplierGroupRepository->createItem();
        return $this->successResponse($this->supplierGroupRepository->getItem($group->id));
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(),
            [
                'title' => 'required|nullable|unique:supplier_groups,title,' . $id . 'id|max:255'
            ]
        );

        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->supplierGroupRepository->updateItem($id);
        return $this->successResponse($this->supplierGroupRepository->getItem($id));
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->supplierGroupRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }
}
