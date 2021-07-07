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
            $model->combinations()->get()->each->delete();

            $groups->map->getAttributes()->map(function($layout) {
                return tap($layout, function(&$layout) {
                    $layout['attributes'] = json_decode($layout['attributes'] ?? '[]', true);
                }); 
            })->each(function($attributes) use ($model) { 
                if (isset($attributes['attributes']) && ! empty($attributes['attributes'])) {

                    $combination = $model->combinations()->create($attributes);
                    $combination->attributes()->sync($attributes['attributes']); 

                } 
            });   
        });
        
    }
}
