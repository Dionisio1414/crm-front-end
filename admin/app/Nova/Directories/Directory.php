<?php

namespace App\Nova\Directories;

use App\Models\Languages\Source\Options;
use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use OptimistDigital\NovaSimpleRepeatable\SimpleRepeatable;

class Directory extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Directories\Directory::class;

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
        'id', 'title'
    ];

    public static $indexDefaultOrder = [
        'id' => 'asc'
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

            Text::make(__('Title'), 'default_language_title')
                ->rules('required', 'max:255')
                ->hideWhenCreating()
                ->hideWhenUpdating(),

            Text::make(__('Directory Section'), 'header.default_language_title')
                ->readonly()
                ->hideFromIndex(),

            Number::make(__('Id directory'), 'directory_id')
                ->rules('required', 'max:255'),

            Image::make(__('Image'), 'thumbnail')->readonly(),

            SimpleRepeatable::make(__('Title'), 'title', [
                Select::make(__('Language'), 'language_id')->options(Options::_getOptions()),
                Text::make(__('Title'), 'title'),
            ]),

        ];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if (empty($request->get('orderBy'))) {
            $query->getQuery()->orders = [];
            return $query->orderBy(key(static::$indexDefaultOrder), reset(static::$indexDefaultOrder));
        }
        return $query;
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

//    public static function label()
//    {
//        return __('Directories');
//    }
//
//    public static function createButtonLabel()
//    {
//        return __('Create Directory'); // TODO: Change the autogenerated stub
//    }
//
//    public static function singularLabel()
//    {
//        return __('Directory');
//    }
}
