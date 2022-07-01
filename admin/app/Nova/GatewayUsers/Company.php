<?php

namespace App\Nova\GatewayUsers;

use App\Helpers\Yesno;
use App\Nova\Resource;
use Google\Type\DateTime;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nikans\TextLinked\TextLinked;
use Wemersonrv\InputMask\InputMask;

class Company extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Users\Company::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name', 'domain'
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            TextLinked::make(__('FIO Owner'), 'fio')
                ->linkResource('gateway-users', $this->user->id ?? Yesno::NO)
                ->onlyOnIndex(),

            Text::make(__('Name Company'), 'name')
                ->rules('required', 'max:255')
                ->sortable(true),

            Text::make(__('Domain'), 'domain')
                ->rules('required', 'max:255')
                ->sortable(true),

            Text::make(__('FIO Owner'), 'fio')
                ->hideFromIndex()
                ->readonly(),

            Date::make(__('Data change prohibition'),'data_prohibition')
                ->hideFromIndex()
                ->readonly(),

            Text::make( __('Currency'),'currency_id')
                ->hideFromIndex()
                ->readonly(),

            Text::make( __('Interface language'),'language_interface_id')
                ->hideFromIndex()
                ->readonly(),

            Number::make( __('Clearing the archive in days'), 'archive_cleare')
                ->hideFromIndex()
                ->readonly(),

            Boolean::make( __('Control the balances of goods of organizations'), 'is_residue_control')
                ->hideFromIndex()
                ->readonly(),

            Boolean::make( __('Printing labels and valuables'), 'is_labels_price_tags')
                ->hideFromIndex()
                ->readonly(),

            Boolean::make( __('Use kits'), 'is_kits')
                ->hideFromIndex()
                ->readonly(),

            Text::make( __('Database Name'), 'db_database')
                ->hideFromIndex()
                ->readonly(),

            Text::make( __('Database Username'), 'db_username')
                ->hideFromIndex()
                ->readonly(),

            Text::make( __('Database Password'), 'db_password')
                ->hideFromIndex()
                ->readonly(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    public static function label()
    {
        return __('Companies');
    }
    public static function singularLabel()
    {
        return __('Company');
    }
}
