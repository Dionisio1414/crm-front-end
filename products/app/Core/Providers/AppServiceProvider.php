<?php

namespace App\Core\Providers;

use App\Core\Model\Yesno;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        /* Validation Json Title */
        Validator::extend('json_lang_title', function($attribute, $value, $parameters) {
            if(isset($parameters[0]) && $parameters[0]){
                $count = DB::table($parameters[0])->where('archive',Yesno::NO)->where(function ($query) use ($value, $parameters){
                    if(isset($parameters[1]) && $parameters[1]){
                        $query->where($parameters[1], 'like', '%"' .  $parameters[1]. '": "' . $value . '"%');
                        $query->orWhere($parameters[1], 'like', '%"' .  $parameters[1]. '": ' . $value . '%');
                    }
                    if(isset($parameters[2]) && $parameters[2] != 'id'){
                        $query->where('id', '!=', (int) $parameters[2]);
                    }
                })->count();

                if($count){
                    return false;
                }
            }

            return true;
        });

    }
}
