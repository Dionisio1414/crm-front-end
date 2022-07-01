<?php

namespace App\Core\Traits;

use App\Languages\Repositories\LanguageRepositories;
use Illuminate\Support\Facades\Auth;

trait CoreServiceCrm
{
    protected $paginate_limit = 10;
    protected $baseUri, $secret, $db_name, $request;

    public function __construct(LanguageRepositories $languageRepositories)
    {
        $user = Auth::guard()->user();

        if(!$user){
            return response()->json([
                'message' => 'Access Denied'
            ], 419);
        }

        $this->baseUri = config('services.crm.base_uri');
        $this->secret = config('services.crm.secret');

        $this->request = request()->all();

        $this->request['paginate'] = $this->request['paginate'] ?? $this->paginate_limit;
        $this->request['language'] = $languageRepositories->getActiveLanguages()->toArray();
        $this->request['lang'] = $languageRepositories->getLanguageById($user->company->language_interface_id)->code ?? config('app.lang');
        $this->request['lang_id'] = $user->company->language_interface_id ?? config('app.lang_id');

        $this->request['db'] = $user->company->db_database;
        $this->request['user_id'] = $user->id;
    }
}
