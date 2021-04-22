<?php

namespace Armincms\Store\Nova;

use Illuminate\Http\Request; 
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\{ID, Text, BelongsTo, Image};
use Yna\NovaSwatches\Swatches;
use Armincms\Fields\Targomaan;

class Attribute extends Resource
{ 
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \Armincms\Store\Models\StoreAttribute::class;  

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
    public static $with = ['group'];

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id'
    ]; 

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchJson = [ 
    ]; 

    /**
     * The columns that should be searched as json.
     *
     * @var array
     */
    public static $searchTranslations = [ 
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

            BelongsTo::make(__('Attribute Group'), 'group', AttributeGroup::class)
                ->showCreateRelationButton()
                ->withoutTrashed()
                ->required()
                ->rules('required'),


            Targomaan::make([
                Text::make(__('Attribute Value'), 'value')
                    ->required()
                    ->rules('required'),

                $this->slugField(),
            ]), 

            $this->mergeWhen($this->isColorGroup($request), function() {
                return [
                    Swatches::make(__('Attribute Color'), 'color')
                        ->showFallback('color')
                        ->nullable(),

                    Image::make(__('Attribute Texture'), 'texture')
                        ->nullable(),
                ];
            }), 
        ];
    }

    public function isColorGroup(Request $request)
    {
        return $request->viaRelationship() && $request->findParentModelOrFail()->type == 'color'
            || data_get($this, 'group.type') == 'color';
    }

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title()
    {
        return (new AttributeGroup($this->group))->title() .': '. parent::title();
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
        return '/resources/'.AttributeGroup::uriKey().'/'.$request->viaResourceId;
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
        return '/resources/'.AttributeGroup::uriKey().'/'.$request->viaResourceId;
    } 
}
