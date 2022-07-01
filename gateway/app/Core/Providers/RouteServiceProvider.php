<?php

namespace App\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{

    protected $modules = [];

    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes()
    {
        foreach($this->getModules() as $module) {
            $path = base_path('app/' . $module . '/Routes/web.php');
            if(file_exists($path)) {
                Route::middleware('web')->namespace('App\\' . $module . '\Controllers')->group($path);
            }
        }
    }

    protected function mapApiRoutes()
    {
        foreach($this->getModules() as $module) {
            $path = base_path('app/' . $module . '/Routes/api.php');
            if(file_exists($path)) {
                Route::prefix('api')->namespace('App\\' . $module . '\Controllers')
                    ->middleware('api')->group($path);
            }
        }
    }

    protected function getModules()
    {
        if(!$this->modules) {
            $path = base_path('app/');
            foreach(scandir($path) as $module) {
                if($module == '.' || $module == '..') {
                    continue;
                }

                $routesPath = base_path('app/' . $module . '/Routes/');
                if(!is_dir($routesPath)) {
                    continue;
                }

                $this->modules[] = $module;
            }
        }
        return $this->modules;
    }

    public function boot()
    {
        \Route::pattern('domain', '[a-z0-9.\-]+');
        parent::boot();
    }
}
