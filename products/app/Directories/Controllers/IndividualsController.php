<?php

namespace App\Directories\Controllers;

use App\Core\Traits\ApiResponder;
use App\Directories\Repositories\Counterparties\CounterpartiesRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;

class IndividualsController extends Controller
{
    use ApiResponder;

    private $counterpartiesRepository;

    public function __construct(CounterpartiesRepository $counterpartiesRepository)
    {
        /* Set database */
        if($db = request('db')){
            DatabaseConnection::setConnection([
                'db_database' => $db
            ]);
        }

        $this->counterpartiesRepository = $counterpartiesRepository;
    }

    public function index(Request $request)
    {
        $data = $this->counterpartiesRepository->getIndividualsByCounterparty($request->counterparty_id);

        $rezult = (object)[
            'list'    => $data,
        ];

        return $this->successResponse($rezult);
    }

}
