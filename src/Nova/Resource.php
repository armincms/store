<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Armincms\Nova\Resource as BaseResource;
use Armincms\Fields\InteractsWithJsonTranslator;

abstract class Resource extends BaseResource
{
    use InteractsWithJsonTranslator;

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Feature::class;

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Store';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name'
    ];  

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchJson = [
        'name'
    ]; 

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
}
