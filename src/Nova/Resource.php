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
        'id'
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
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchTranslation = [

    ]; 

    /**
     * Apply the search query to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function applySearch($query, $search)
    {
        $columns = static::searchableTranslationColumns();

        return parent::applySearch($query, $search)->when(! empty($columns), function($query) use ($search) {
            $query->orWhere(function ($query) use ($search) {
                $query->whereHas('translations', function($query) use ($search) { 
                    $query->where(function($query) use ($search) {
                        static::applyTranslationSearch($query, $search);
                    }); 
                });
            });
        });
    }

    /**
     * Apply the search query to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function applyTranslationSearch($query, $search)
    {
        $likeOperator = $query->getModel()->getConnection()->getDriverName() == 'pgsql' 
                                    ? 'ilike' : 'like';

        foreach (static::searchableTranslationColumns() as $column) {
            $query->orWhere($query->qualifyColumn($column), $likeOperator, '%'.$search.'%');
        }

        return $query;
    }

    /**
     * Get the searchable columns for the resource.
     *
     * @return array
     */
    public static function searchableTranslationColumns()
    {
        return static::$searchTranslation;
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
}
