<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\DirectoriesRepository;
use App\Languages\Repositories\LanguageRepositories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;
use App\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DirectoriesController extends Controller
{
    use ApiResponder;

    private $directoryRepository, $languageRepositories, $provider, $request, $lang;

    public function __construct(DirectoriesRepository $directoryRepository, LanguageRepositories $languageRepositories)
    {
        $user = Auth::guard()->user();

        $this->directoryRepository = $directoryRepository;
        $this->languageRepositories = $languageRepositories;
        $this->provider = request()->directory;

        $this->lang = $this->languageRepositories->getLanguageById($user->company->language_interface_id)->code ?? config('app.lang');
    }

    /**
     * @OA\Get(
     *      path="/directories/core/{name_directory}/list",
     *      tags={"Directory"},
     *      summary="Get Directories Core",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                 property="data",
     *                 title="All Directories COre",
     *                 type="array",
     *                 collectionFormat="multi",
     *                 @OA\Items(
     *                   @OA\Property(
     *                      property="directory_id",
     *                      title="directory_id",
     *                      example=1,
     *                      type="int"
     *                   ),
     *                   @OA\Property(
     *                      property="title",
     *                      title="title",
     *                      example="Title directory",
     *                      type="string"
     *                  )
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
        $directories = $this->directoryRepository->getDirectory($this->provider);

        if($directories){
            foreach ($directories->items as $item){
                $item['title'] = $this->languageRepositories->_setSingleLanguageArray($item['title'],  $this->lang);
            }
        }

        return $this->validResponse(
            optional($directories)->items,
            Response::HTTP_OK
        );
    }
}
