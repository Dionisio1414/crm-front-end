<?php

namespace App\Directories\Controllers;

use App\Languages\Repositories\LanguageRepositories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;
use App\Core\Http\Controllers\Controller;

class LanguageController extends Controller
{
    use ApiResponder;

    private $languageRepository;

    public function __construct(LanguageRepositories $languageRepository)
    {
        $this->languageRepository = $languageRepository;
    }

    /**
     * @OA\Get(
     *      path="/directories/languages",
     *      tags={"Directory"},
     *      summary="Get Languages",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Languages",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                     ref="#/components/schemas/Language"
     *                 )
     *              )
     *          )
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized user",
     *      ),
     * )
     */

    public function index()
    {
        return $this->validResponse(
            $this->languageRepository->getLanguages(),
            Response::HTTP_OK
        );
    }
}
