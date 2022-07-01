<?php

namespace App\Nomenclatures\Controllers;

use App\Core\Traits\ApiResponder;
use App\Directories\Repositories\TypesPrices\TypesPriceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Http\Controllers\Controller;
use App\Core\Helpers\DatabaseConnection;
use App\Nomenclatures\Traits\GenerateProducts;

class GenerateController extends Controller
{
    use ApiResponder, GenerateProducts;

    protected $request, $typesPriceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(TypesPriceRepository $typesPriceRepository)
    {
        /* Set database */
        $db = request('db');
        DatabaseConnection::setConnection([
            'db_database' => $db
        ]);

        $this->request = request()->all();
        $this->typesPriceRepository = $typesPriceRepository;
    }

    public function generate(): JsonResponse
    {
        return $this->successResponse($this->generateProducts(), Response::HTTP_OK);
    }
}
