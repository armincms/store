<?php

namespace Armincms\Store\Nova\Flexible\Resolvers;

use Illuminate\Support\Arr;
use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class Combination implements ResolverInterface
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
        return $resource->combinations()->with('attributes')->get()->map(function($combination) use ($layouts) {
            $layout = $layouts->find('combination');

            if(!$layout) return;

            return $layout->duplicateAndHydrate($combination->id, array_merge($combination->toArray(), [ 
                'attributes' => $combination->attributes,
            ]));
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
            $groups->map->getAttributes()->filter(function($attributes) { 
                return isset($attributes['attributes']) && ! empty($attributes['attributes']);
            })->sortBy(function($attributes) {
                // when updating attributes we make uniquify with the attributes relationship
                // so we sort by attributes count to ensure joint attributes do not override others 
                return count($attributes['attributes'] ?? []);
            })->each(function($attributes) use ($model) { 
                $combination = $model->combinations()->whereHas('attributes', function($query) use ($attributes) {
                    $query->whereKey($attributes['attributes'] ?? []);
                }, count($attributes['attributes'] ?? []))->updateOrCreate([], $attributes);

                $combination->attributes()->sync($attributes['attributes'] ?? []); 
            }); 

            $model->combinations()->whereDoesntHave('attributes', function($query) use ($groups) {
                $query->whereKey($groups->map->getAttributes()->flatMap->attributes->all());
            })->delete(); 
        });
        
    }
}
