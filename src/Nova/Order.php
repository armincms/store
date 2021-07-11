<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request;  
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Badge; 
use Laravel\Nova\Fields\DateTime; 
use Laravel\Nova\Fields\HasMany; 
use Laravel\Nova\Fields\ID; 
use Laravel\Nova\Fields\Text; 
use Laravel\Nova\Fields\Textarea; 

class Order extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreOrder::class;  

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'tracking_code';

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

            Text::make(__('Tracking Code'), 'tracking_code')->sortable(),  

            Badge::make(__('Order Status'), 'marked_as')->map([
                'draft' => 'danger',
                'delivered' => 'success',
                'paid' => 'success',
                'pending' => 'warning',
                'shipped' => 'info',
            ]),

            $this->priceField(__('Total Price'), 'total', $this->currency_code),

            DateTime::make(__('Created At'), 'created_at'),

            Textarea::make(__('Shipping Address'), 'address')
                ->hideFromIndex(),

            HasMany::make(__('Order Items'), 'saleables', OrderItem::class),
        ];
    } 

    /**
     * Get the actions available on the entity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [
            new Actions\Shipped,

            new Actions\Delivered,
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
        if ($request instanceof \Laravel\Nova\Http\Requests\ActionRequest) {
            return true;
        }

        return in_array($ability, ['viewAny', 'view']) ? parent::authorizedTo($request, $ability) : false;
    }
}
