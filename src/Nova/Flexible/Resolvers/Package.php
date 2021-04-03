<?php

namespace Armincms\Store\Nova\Flexible\Resolvers;

use Whitecube\NovaFlexibleContent\Value\ResolverInterface;

class Package implements ResolverInterface
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
        return $resource->products()->get()->map(function($product) use ($layouts) {
            $layout = $layouts->find('product');

            if(!$layout) return;

            return $layout->duplicateAndHydrate($product->id, [
                'count' => $product->pivot->count,
                'product' => $product->id,
            ]);
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
            $products = $groups->map->getAttributes()->keyBy('product')->map(function($attributes) {
                return collect($attributes)->only('count'); 
            })->toArray(); 

            $model->products()->sync($products);
        });
        
    }
}
