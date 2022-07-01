<?php

namespace App\Directories\Controllers\Individuals;

use App\Directories\Repositories\IndividualsDocuments\IndividualsDocumentsRepository;
use App\Directories\Repositories\IndividualsDocuments\IndividualsDocumentsHeaderRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class DocumentsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $individualsDocumentsRepository, $individualsDocumentsHeaderRepository;

    public function __construct(IndividualsDocumentsRepository $individualsDocumentsRepository,
                                IndividualsDocumentsHeaderRepository $individualsDocumentsHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->individualsDocumentsRepository = $individualsDocumentsRepository;
        $this->individualsDocumentsHeaderRepository = $individualsDocumentsHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->individualsDocumentsRepository->getItemsTable();
        $unitsHeader = $this->individualsDocumentsHeaderRepository->getHeaders();

        $rezult = (object)[
            'headers' => $unitsHeader,
            'body'    => $units['data'],
            'total_page'   => $units['last_page'],
            'total'   => $units['total']
        ];

        return $this->successResponse($rezult);
    }

    public function index()
    {
        //all list
    }

    public function store(Request $request)
    {
        $item = $this->individualsDocumentsRepository->createItem();
        $this->individualsDocumentsRepository->createOrUpdateCellsContact($item->supplier_contact_id, $item);

        return $this->successResponse($this->individualsDocumentsRepository->getItem($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->individualsDocumentsRepository->updateItem($id);
        $this->individualsDocumentsRepository->createOrUpdateCellsContact($item->supplier_contact_id, $item);

        return $this->successResponse($this->individualsDocumentsRepository->getItem($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->individualsDocumentsHeaderRepository->updateHeaders();

        return $this->successResponse($this->individualsDocumentsHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->individualsDocumentsRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
