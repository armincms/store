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
        return $resource->combinations()->with('values')->get()->map(function($combination) use ($layouts) {
            $layout = $layouts->find('combination');

            if(!$layout) return;

            return $layout->duplicateAndHydrate($combination->id, array_merge($combination->toArray(), [ 
                'values' => $combination->values,
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
            $groups->map->getAttributes()->sortBy(function($attributes) {
                // when updating values we make uniquify with the values relationship
                // so we sort by values count to ensure joint values do not override others 
                return count($attributes['values'] ?? []);
            })->each(function($attributes) use ($model) { 
                $combination = $model->combinations()->whereHas('values', function($query) use ($attributes) {
                    $query->whereKey($attributes['values'] ?? []);
                }, count($attributes['values'] ?? []))->updateOrCreate([], $attributes);

                $combination->values()->sync($attributes['values'] ?? []); 
            }); 
        });
        
    }
}
