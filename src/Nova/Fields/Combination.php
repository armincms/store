<?php

namespace Armincms\Store\Nova\Fields;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\{Number, Boolean}; 
use Armincms\Fields\BelongsToMany;
use Armincms\Nova\Fields\Money;


class Combination 
{

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static function fields(Request $request)
    {  
        return [ 
            Number::make(__('Quantity'), 'quantity')
                ->required()
                ->rules('required')
                ->min(1)
                ->help(__('Quantity of this combination')),

            Money::make(__('Impact On Price'), 'price') 
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
                ->help(__('Default combination on the user purchase.')),
        ];
    }
}