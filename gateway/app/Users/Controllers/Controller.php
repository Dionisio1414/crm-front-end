<?php

namespace App\Users\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Core\Http\Controllers\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }

}
