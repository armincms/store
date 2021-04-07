<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, Number, Boolean, Select, BelongsTo}; 
use Armincms\Fields\{Targomaan, BelongsToMany};
use Armincms\Nova\Fields\Money;
use Armincms\Store\Helper;

class Carrier extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreCarrier::class;  

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
                Text::make(__('Carrier Name'), 'name')
                    ->required()
                    ->rules('required'),

                Text::make(__('Transit Time'), 'transit_time')
                    ->required()
                    ->rules('required'),
            ]),  

            Number::make(__('Speed Grade'), 'config->speed_grade')
                ->max(10)
                ->min(-10)
                ->step(0.1)
                ->required()
                ->rules('required'),

            Select::make(__('Billing Behavior'), 'config->billing_behavior')
                ->options(Helper::shippingBillings())
                ->displayUsingLabels()
                ->hideFromIndex()
                ->required()
                ->rules('required'),

            BelongsToMany::make(__('Cost Per Range'), 'ranges', \Armincms\Location\Nova\Zone::class)
                ->hideFromIndex()
                ->pivots()
                ->fields(function($request) { 
                    return [
                        Money::make(__('Carrier Price'), 'price')
                            ->required()
                            ->rules('required'), 

                        Number::make(__('Minimum'), 'min')
                            ->required()
                            ->rules('required'),

                        Number::make(__('Maximum'), 'max')
                            ->nullable(),
                    ];
                }),

            Select::make(__('Out-of-range Behavior'), 'config->outof_range')
                ->options(Helper::outofRangeBehaviors())
                ->displayUsingLabels()
                ->hideFromIndex()
                ->required()
                ->rules('required'),

            BelongsTo::make(__('Carrier Tax'), 'tax', \Armincms\Taxation\Nova\Tax::class)
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->nullable(),

            Text::make(__('Tracking URL'), 'config->tracking_url')
                ->hideFromIndex()
                ->nullable(),

            Boolean::make(__('Free Shipping'), 'free_shipping'), 
            
            $this->imageField(__('Logo'), 'logo')
                    ->conversionOnPreview('logo-thumbnail') 
                    ->conversionOnDetailView('logo-thumbnail') 
                    ->conversionOnIndexView('logo-icon'),
        ];
    }
}
