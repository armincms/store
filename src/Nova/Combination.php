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
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    { 
        return [
            ID::make(__('ID'), 'id')->sortable(),

            BelongsTo::make(__('Product'), 'product', Product::class)
                ->required()
                ->rules('required')
                ->withoutTrashed(), 

            BelongsToMany::make(__('Attributes'), 'values', AttributeValue::class) 
                ->searchable()
                ->required()
                ->rules('required'), 

            Number::make(__('Quantity'), 'quantity')
                ->required()
                ->rules('required')
                ->min(0)
                ->help(__('Quantity of this combination')),

            $this->priceField(__('Impact On Price'))
                ->required()
                ->rules('required')
                ->min(0)
                ->help(__('How much price will be increased?')),

            Number::make(__('Impact On Weight'), 'weight')
                ->required()
                ->rules('required')
                ->min(0)
                ->help(__('How much weight will be increased?')),

            Number::make(__('Impact On Width'), 'width')
                ->required()
                ->rules('required')
                ->min(0)
                ->help(__('How much width will be increased?')),

            Number::make(__('Impact On Height'), 'height')
                ->required()
                ->rules('required')
                ->min(0)
                ->help(__('How much height will be increased?')),

            Boolean::make(__('Default Combination'), 'default')
                ->help(__('Default combination on the user purchase.'))
        ];
    }
}
