<?php

namespace App\Filters\Controllers\Counterparties;

use App\Filters\Repositories\Counterparties\СontractsRepository;
use App\Core\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class ContractsController extends Controller
{
    use ApiResponder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $contractsRepository;

    public function __construct(СontractsRepository $contractsRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->contractsRepository = $contractsRepository;
    }

    public function index()
    {
        return $this->successResponse($this->contractsRepository->filter());
    }

    public function store()
    {
        $this->contractsRepository->update();
        return $this->successResponse($this->contractsRepository->filter());
    }
}
