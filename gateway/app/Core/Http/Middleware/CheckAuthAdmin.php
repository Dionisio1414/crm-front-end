<?php

namespace App\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Core\Traits\ApiResponder;

class CheckAuthAdmin
{

    use ApiResponder;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$validSecrets = explode(',', env('ACCEPTED_SECRETS'));
        //if (in_array($request->header('Authorization'), $validSecrets, true)) {
        return $next($request);
        // }
        //return $this->errorResponse('Unauthorized',Response::HTTP_UNAUTHORIZED);
    }
}
