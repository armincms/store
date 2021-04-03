<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, HasMany};
use Armincms\Fields\Targomaan;

class Feature extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreFeature::class;  

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            ID::make(__('ID'), 'id')->sortable(),

            Targomaan::make([
                Text::make(__('Feature name'), 'name')
                    ->required()
                    ->rules('required'),
            ]), 

            HasMany::make(__('Values'), 'values', FeatureValue::class),
        ];
    }
}
