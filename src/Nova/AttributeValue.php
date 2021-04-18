<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, BelongsTo};
use Armincms\Fields\Targomaan;

class AttributeValue extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreAttributeValue::class;  

    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 25;

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'value';

    /**
     * The relationships that should be eager loaded when performing an index query.
     *
     * @var array
     */
    public static $with = ['attribute'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'value'
    ]; 

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchJson = [
        'value'
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

            BelongsTo::make(__('Attribute'), 'attribute', Attribute::class)
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->required()
                ->rules('required'),

            Targomaan::make([
                with($request->findParentModel(), function($attributeResource) { 
                    $fieldClass = optional($attributeResource)->field ?? Text::class;

                    return $fieldClass::make(__('Attribute Value'), 'value')
                            ->required()
                            ->rules('required');
                }),                
            ]), 
        ];
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return (new Attribute($this->attribute))->title() .': '. parent::title();
    }

    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        return '/resources/'.Attribute::uriKey().'/'.$request->viaResourceId;
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        return '/resources/'.Attribute::uriKey().'/'.$request->viaResourceId;
    } 
}
