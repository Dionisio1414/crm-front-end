<?php

namespace App\Providers;

use App\Nova\User;
use App\Nova\GatewayUsers\Company;
use App\Nova\GatewayUsers\GatewayUsers;
use App\Nova\Directories\DirectoryHeaders;
use App\Nova\Resource;
use DigitalCreative\CollapsibleResourceManager\CollapsibleResourceManager;
use DigitalCreative\CollapsibleResourceManager\Resources\Group;
use DigitalCreative\CollapsibleResourceManager\Resources\TopLevelResource;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Silvanite\Brandenburg\Role;
use Silvanite\NovaToolPermissions\NovaToolPermissions;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
//        Gate::define('viewNova', function ($user) {
//            return in_array($user->email, [
//                //
//            ]);
//        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            new Help,
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaToolPermissions(),

            new CollapsibleResourceManager([
                'navigation' => [
                    TopLevelResource::make([
                        'label' => __('Main'),
                        'resources' => [
                            GatewayUsers::class,
                            Company::class
                        ]
                    ]),
                    TopLevelResource::make([
                        'label' => __('Settings'),
                        'resources' => [
                            DirectoryHeaders::class
                        ],
                    ]),
                    TopLevelResource::make([
                        'label' => __('Admin Panel'),
                        'resources' => [
                            User::class,
                            \Silvanite\NovaToolPermissions\Role::class
                        ],
                    ]),
                ]
            ])
            /* .. */
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
