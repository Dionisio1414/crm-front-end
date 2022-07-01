<?php

namespace App\Directories\Controllers;

use App\Directories\Repositories\DirectoriesRepository;
use App\Languages\Repositories\LanguageRepositories;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;
use App\Core\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminDirectoriesController extends Controller
{
    use ApiResponder;

    private $directoryRepository, $provider;

    public function __construct(DirectoriesRepository $directoryRepository)
    {
        $this->directoryRepository = $directoryRepository;
        $this->provider = request()->directory;
    }

    public function index()
    {
        $directories = $this->directoryRepository->getDirectory($this->provider);

        return $this->validResponse(
            optional($directories)->items,
            Response::HTTP_OK
        );
    }

    public function show($directory, $id)
    {
        $directories = $this->directoryRepository->getDirectoryById($this->provider, $id);

        return $this->validResponse(
            optional($directories)->items,
            Response::HTTP_OK
        );
    }
}
