<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Number, Boolean, BelongsTo, HasMany};
use Armincms\Fields\BelongsToMany; 

class Combination extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreCombination::class; 

    /**
     * Indicates if the resource should be globally searchable.
     *
     * @var bool
     */
    public static $globallySearchable = false; 

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['values', 'product'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return array_merge([
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('Product'), 'product', Product::class)
                ->required()
                ->rules('required')
                ->withoutTrashed(),
                  
            BelongsToMany::make(__('Attributes'), 'values', \Armincms\Store\Nova\AttributeValue::class) 
                ->searchable()
                ->required()
                ->rules('required'), 
                
        ], Fields\Combination::fields($request));
    }
}
