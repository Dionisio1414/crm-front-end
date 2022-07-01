<?php

namespace App\Core\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="EasySell Documentation",
 *      description="EasySell description",
 *      @OA\Contact(
 *          email="admin@admin.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="EasySell API Server not authorized"
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST_COMPANY,
 *      description="EasySell API Server authorized company"
 * )
 */

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
