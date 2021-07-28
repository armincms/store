<?php

namespace Armincms\Store\Nova\Fields;

use Armincms\Location\Nova\City;
use Armincms\Location\Nova\State;
use Armincms\Location\Nova\Country;
use Armincms\Location\Nova\Zone;
use Laravel\Nova\Panel;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest; 

class Range extends Field
{  
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'armincms-store-range'; 

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var \Closure|bool
     */
    public $showOnIndex = false; 

    /**
     * Indicates if the element should be shown on the detail view.
     *
     * @var \Closure|bool
     */
    public $showOnDetail = false;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|callable|null  $attribute
     * @param  callable|null  $resolveCallback
     * @return void
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'city'  => City::uriKey(),
            'country' => Country::uriKey(),
            'state' => State::uriKey(),
            'zone' => Zone::uriKey(),
            'maxRange' => 4,
        ]);
    }

    /**
     * Hydrate the given attribute on the model based on the incoming request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  string  $requestAttribute
     * @param  object  $model
     * @param  string  $attribute
     * @return mixed
     */
    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        return function() use ($request, $requestAttribute, $model, $attribute) {         
            $value = collect(json_decode($request[$requestAttribute] ?? [], true));
            $model->countries()->sync([]);
            $model->states()->sync([]);
            $model->cities()->sync([]);
            $model->zones()->sync([]);

            $value->groupBy('resource')->map(function($values, $relation) use ($model) {
                $ids = $values->keyBy('id')->map(function($value) {
                    return [
                        'ranges' => json_encode($value['values']),
                    ];
                });

                $model->{$relation}()->sync($ids);
            });
        }; 
    }

    /**
     * Resolve the given attribute from the given resource.
     *
     * @param  mixed  $resource
     * @param  string  $attribute
     * @return mixed
     */
    protected function resolveAttribute($resource, $attribute)
    {
        $resource->load('countries', 'states', 'cities', 'zones');

        return tap([
            'countries' => $this->resolveRelation($resource, 'countries'),
            'states' => $this->resolveRelation($resource, 'states'),
            'cities' => $this->resolveRelation($resource, 'cities'),
            'zones' => $this->resolveRelation($resource, 'zones'),
        ], function($values) { 
            $this->withMeta([
                'range' => collect($values)->flatten(1)->max(function($values) {
                    return count($values);
                }) ?: 1
            ]);
        });
    }

    public function resolveRelation($resource, $relation)
    {
        return collect($resource->getRelationValue($relation))->mapWithKeys(function($model) { 
            return [
                $model->getKey() => json_decode($model->pivot->ranges, true) ?? ['0'],
            ];
        });
    }

    /**
     * Set the country resource name.
     * 
     * @param  string $resourceName 
     * @return $this               
     */
    public function country(string $resourceName)
    {
        return $this->withMeta([
            'country' => $resourceName,
        ]);
    }

    /**
     * Set the state resource name.
     * 
     * @param  string $resourceName 
     * @return $this               
     */
    public function state(string $resourceName)
    {
        return $this->withMeta([
            'state' => $resourceName,
        ]);
    }

    /**
     * Set the city resource name.
     * 
     * @param  string $resourceName 
     * @return $this               
     */
    public function city(string $resourceName)
    {
        return $this->withMeta([
            'city' => $resourceName,
        ]);
    }

    /**
     * Set the zone resource name.
     * 
     * @param  string $resourceName 
     * @return $this               
     */
    public function zone(string $resourceName)
    {
        return $this->withMeta([
            'zone' => $resourceName,
        ]);
    }
}