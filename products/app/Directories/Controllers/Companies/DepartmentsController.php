<?php

namespace App\Directories\Controllers\Companies;

use App\Core\Model\Yesno;
use App\Directories\Repositories\Companies\DepartmentRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->departmentRepository = $departmentRepository;

        app()->setLocale(request()->lang);
    }

    public function getTable()
    {
        $main = $this->departmentRepository->getAll();

        $data = $main->toArray();

        $rezult = (object) [
            'list'=>$data['data'],
            'total_page'=>$data['last_page'],
            'total'=>$data['total'],
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        $data = $this->departmentRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->departmentRepository->createOrUpdateItem();
        $this->departmentRepository->createOrUpdateEmployees($item->id);

        return $this->successResponse($this->departmentRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->departmentRepository->createOrUpdateItem($id);
        $this->departmentRepository->createOrUpdateEmployees($item->id);

        return $this->successResponse($this->departmentRepository->getItem($item->id));
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->departmentRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function storeAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {
            $table = 'directory_companies_departments';
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'json_lang_title:' . $table . ',' . $key . ',' . $request->id . 'id|max:255',
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
            $table = 'directory_companies_departments';
            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'json_lang_title:' . $table . ',' . $key . ',' . $request->id . 'id|max:255',
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
