<?php

namespace Armincms\Store\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\BooleanFilter;
use Armincms\Store\Helper;

class ProductDetails extends BooleanFilter
{
    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    { 
        $productTypes = collect($value)->only(array_keys(Helper::productTypes()))->filter();
        $productStatuses = collect($value)->only(array_keys(Helper::productStatuses()))->filter();

        return $query->when($productTypes->isNotEmpty(), function($query) use ($productTypes) {
                        $query->whereIn('product_type', $productTypes->keys()->all());
                    })
                    ->when($productStatuses->isNotEmpty(), function($query) use ($productStatuses) {
                        $query->whereIn('product_status', $productStatuses->keys()->all());
                    })
                    ->when(data_get($value, 'active'), function($query) {
                        $query->active();
                    })
                    ->when(data_get($value, 'on_sale'), function($query) {
                        $query->onSale();
                    })
                    ->when(data_get($value, 'only_online'), function($query) {
                        $query->onlyOnline();
                    })
                    ->when(data_get($value, 'available'), function($query) {
                        $query->available();
                    });
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return array_merge(array_flip(array_merge(Helper::productTypes(), Helper::productStatuses())), [
            __('Active Products') => 'active',
            __('On Sale') => 'on_sale',
            __('Available For Sale') => 'available',
            __('Only Online Sale') => 'only_online',
        ]);
    }
}
