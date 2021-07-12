<?php

namespace Armincms\Store\Nova\Flexible\Resolvers;

use Armincms\Location\Nova\City;
use Armincms\Location\Nova\State;
use Armincms\Location\Nova\Country;
use Armincms\Location\Nova\Zone;
use Illuminate\Support\Arr;
use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class Range implements ResolverInterface
{
    /**
     * get the field's value
     *
     * @param  mixed  $resource
     * @param  string $attribute
     * @param  Whitecube\NovaFlexibleContent\Layouts\Collection $layouts
     * @return Illuminate\Support\Collection
     */
    public function get($resource, $attribute, $layouts)
    {  
        return collect([
            Country::class, 
            State::class, 
            City::class, 
            Zone::class
        ])->flatMap(function($resourceClass) use ($layouts, $resource) { 
            $locations = $resource->getRelationValue($resourceClass::uriKey());
            $layout = $layouts->find($resourceClass::uriKey());

            if ($layout && $locations) {
                return $locations->map(function($location) use ($layout) {  
                    return $layout->duplicateAndHydrate(md5($location), $location->pivot->toArray()); 
                });
            }
        })->filter(); 
    }

    /**
     * Set the field's value
     *
     * @param  mixed  $model
     * @param  string $attribute
     * @param  Illuminate\Support\Collection $groups
     * @return string
     */
    public function set($model, $attribute, $groups)
    { 
        $model::saved(function ($model) use ($groups) {  
            $groups->groupBy(function($group) {
                return $group->name();
            })->map(function($groups) {
                return $groups->map->getAttributes()->keyBy('location_id');
            })->each(function($attributes, $relation) use ($model) {
                $model->{$relation}()->sync(collect($attributes)->except('attributes')); 
            });   
        });
        
    }
}
