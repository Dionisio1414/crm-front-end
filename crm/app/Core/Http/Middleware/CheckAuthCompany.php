<?php

namespace App\Core\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Users\Model\User\Company;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CheckAuthCompany extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $company = Company::find(Auth::guard($guard)->user()->company_id);
            if($request->route('domain') !== $company->domain){
                return response()->json([
                    'message' => 'Access Denied'
                ], 419);
            }

            return $next($request);
        }

        return response()->json([
            'message' => 'Access Denied'
        ], 419);
    }
}
