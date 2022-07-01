<?php

namespace App\Directories\Controllers\Employee;

use App\Directories\Repositories\Employee\DocumentRepository;
use App\Directories\Repositories\Employee\DocumentHeaderRepository;
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

    private $documentRepository, $documentHeaderRepository;

    public function __construct(DocumentRepository $documentRepository,
                                DocumentHeaderRepository $documentHeaderRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->documentRepository = $documentRepository;
        $this->documentHeaderRepository = $documentHeaderRepository;
    }

    public function getTable(): JsonResponse
    {
        $units = $this->documentRepository->getItemsTable();
        $unitsHeader = $this->documentHeaderRepository->getHeaders();

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
        $item = $this->documentRepository->createItem();
        $this->documentRepository->createOrUpdateCellsById($item->id);

        return $this->successResponse($this->documentRepository->getItemWithCell($item->id));
    }

    public function update(Request $request, $id)
    {
        $item = $this->documentRepository->updateItem($id);
        $this->documentRepository->createOrUpdateCellsById($item->id);

        return $this->successResponse($this->documentRepository->getItemWithCell($id));
    }

    public function updateHeader(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentHeaderRepository->updateHeaders();

        return $this->successResponse($this->documentHeaderRepository->getHeaders());
    }

    public function toArchive(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        $this->documentRepository->toArchive();

        return $this->successMessage('Successful operation', Response::HTTP_OK);
    }

}
