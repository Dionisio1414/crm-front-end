<?php

namespace App\Core\Console;

//use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Gecche\Multidomain\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $modules = [];
    
    protected function commands()
    {
        foreach ($this->getModules() as $module) {
            $path = base_path('app/' . $module . '/Commands');
            if(is_dir($path)) {
                $this->load($path);
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

                $routesPath = base_path('app/' . $module . '/Commands/');
                if(!is_dir($routesPath)) {
                    continue;
                }

                $this->modules[] = $module;
            }
        }
        return $this->modules;
    }

}
