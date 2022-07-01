<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\Organizations\OrganizationRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\Validator;

class OrganizationsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $organizationRepository;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->organizationRepository = $organizationRepository;

        app()->setLocale(request()->lang);
    }

    public function getTable()
    {
        $main = $this->organizationRepository->getAll();

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
        $data = $this->organizationRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function store(Request $request)
    {
        $item = $this->organizationRepository->createOrUpdateItem();
        $this->organizationRepository->createOrUpdateMain($item->id);
        $this->organizationRepository->createOrUpdateContact($item->id);
        $this->organizationRepository->createOrUpdateValues($item->id);

        return $this->successResponse($this->organizationRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->organizationRepository->createOrUpdateItem($id);
        $this->organizationRepository->createOrUpdateMain($item->id);
        $this->organizationRepository->createOrUpdateContact($item->id);
        $this->organizationRepository->createOrUpdateValues($item->id);

        return $this->successResponse($this->organizationRepository->getItem($item->id));
    }

    public function toArchive(Request $request)
   {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->organizationRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

    public function storeAsyncValidations(Request $request)
    {
        foreach ($request->get('validate') as $key => $validate) {

            $table = 'directory_organizations_details_main';
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

            $table = 'directory_organizations_details_main';

            $id = '';
            if($request->id){
                $organization = $this->organizationRepository->getItem($request->id);
                if(optional($organization)->main){
                    $id = $organization->main->id;
                }
            }

            $validator = Validator::make($request->get('validate'),
                [
                    $key => 'unique:' . $table . ',' . $key . ',' . $id . 'id|max:255',
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
