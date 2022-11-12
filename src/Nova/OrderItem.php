<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request;   
use Laravel\Nova\Fields\BelongsTo;   
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Number; 
use Laravel\Nova\Fields\Text;   

class OrderItem extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\OrderItem::class;  

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['order', 'product'];

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'tracking_code'
    ];   

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

            Text::make(__('Product Name'), 'name'),

            Number::make(__('Quantity'), 'count'),

            $this->priceField('Price', 'sale_price'),

            $this->priceField(__('Total Price'), 'sale_price')->resolveUsing(function() {
                return $this->total();
            }), 

            Text::make(__('Carrier'), function() {
                $names = (array) data_get($this->details, 'carrier.name');

                return $names[app()->getLocale()] ?? array_shift($names);
            }),

            BelongsTo::make(__('Related Product'), 'product', Product::class),
        ];
    } 

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [ 
        ];
    }  

    /**
     * Determine if the current user can create new resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * Determine if the current user can view the given resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $ability
     * @return bool
     */
    public function authorizedTo(Request $request, $ability)
    {
        return in_array($ability, ['viewAny']) ? parent::authorizedTo($request, $ability) : false;
    }
}
