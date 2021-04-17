<?php

namespace Armincms\Store\Nova\Fields;

use Laravel\Nova\Panel;
use Laravel\Nova\Fields\{Text, Select};
use Whitecube\NovaFlexibleContent\Flexible;
use Armincms\Store\Nova\Feature;


class Features extends Panel
{
    /**
     * Create a new panel instance.
     *
     * @param  string  $name
     * @param  \Closure|array  $fields
     * @return void
     */
    public function __construct($name, $fields = [])
    {
    	return parent::__construct($name, Feature::newModel()->with('values')->get());
    }

    /**
     * Prepare the given fields.
     *
     * @param  \Closure|array  $features
     * @return array
     */
    protected function prepareFields($features)
    {
    	if ($features->isEmpty()) {
    		return [];
    	}

    	$callback = function($flexible) use ($features) { 
	        $flexible->resolver(\Armincms\Store\Nova\Flexible\Resolvers\Feature::class)
	                 ->button(__('Append Feature To Product'))
	                 ->collapsed();

	        $features->map(function($feature) use ($flexible) { 
	            $flexible->addLayout($feature->name, "__{$feature->getKey()}__", [
	                Select::make(__('Select Feature Value'), 'value')
	                    ->options($feature->values->pluck('value', 'id')->all()),

	                Text::make(__('Or Create Custom Value'), 'custom'),
	            ]);
	        }); 
	    };

    	return parent::prepareFields([
            tap(Flexible::make(__('Product Features'), 'features'), $callback)
        ]); 
    }
}