<?php

namespace App\Core\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['prefix' => 'api', 'middleware' => ['auth.socket:api']]);

        require base_path('app/Services/Routes/channels.php');
    }
}
