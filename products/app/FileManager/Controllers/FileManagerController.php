<?php

namespace App\FileManager\Controllers;

use App\FileManager\Repositories\FileManagerRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class FileManagerController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $fileManagerRepository;

    public function __construct(FileManagerRepository $fileManagerRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->fileManagerRepository = $fileManagerRepository;
    }

    public function show($id)
    {
        return $this->successResponse($this->fileManagerRepository->getFile($id));
    }

    public function store()
    {
        $file = $this->fileManagerRepository->createFile();
        return $this->successResponse($this->fileManagerRepository->getFile($file->id));
    }

    public function list()
    {
        $data = $this->fileManagerRepository->list();

        $rezult = (object)[
            'list'    => $data['data'],
            'total_page'   => $data['last_page'],
            'total'   => $data['total']
        ];

        return $this->successResponse($rezult);
    }

    public function delete(Request $request)
    {
        if(!$request->data){
            return $this->errorResponse('', Response::HTTP_BAD_REQUEST);
        }

        return $this->successResponse($this->fileManagerRepository->delete());
    }
}
