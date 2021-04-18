<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Fields\{ID, Text, Number, Select, HasMany};
use Armincms\Fields\Targomaan;

class Attribute extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreAttribute::class;  

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

            Select::make(__('Attribute Type'), 'field')
                ->options([
                    Text::class => __('Text'),
                    Number::class => __('Number'),
                ])
                ->displayUsingLabels(),

            Targomaan::make([
                Text::make(__('Attribute name'), 'name')
                    ->required()
                    ->rules('required'),
            ]), 

            HasMany::make(__('Values'), 'values', AttributeValue::class),
        ];
    }
}
