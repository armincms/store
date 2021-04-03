<?php

namespace Armincms\Store\Nova\Flexible\Resolvers;

use Whitecube\NovaFlexibleContent\Value\ResolverInterface;
use Armincms\Store\Models\StoreFeatureValue;

class Feature implements ResolverInterface
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
        return $resource->values()->get()->map(function($featureValue) use ($layouts) {
            $layoutName = "__{$featureValue->feature_id}__";
            $layout = $layouts->find($layoutName);

            if(!$layout) return;

            return $layout->duplicateAndHydrate($layoutName, [
                'value' => $featureValue->getKey(), 
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
            $model->values()->sync($this->getProductIdsFromLayouts($groups));
        }); 
    }
    /**
     * get product ids from the given layouts.
     * 
     * @param  Illuminate\Support\Collection $groups
     * @return array
     */
    public function getProductIdsFromLayouts($groups)
    {
        return $groups->groupBy->name()->flatMap(function($layouts, $featureId) {
            return $layouts->map(function($layout) use ($featureId) { 
                if (empty($layout->custom)) {
                    return intval($layout->value);
                }

                return $this->createNewFeatureValue(preg_replace('/[^0-9]+/', '', $featureId), $layout->custom);
            })->filter(); 
        })->toArray(); 
    }

    public function createNewFeatureValue($featureId, $value)
    {
        return StoreFeatureValue::unguarded(function() use ($featureId, $value) {
            $attributes = [
                'value' => $value, 
                'feature_id' => $featureId
            ];
            
            return StoreFeatureValue::firstOrCreate($attributes)->getKey();
        }); 
    }
}
